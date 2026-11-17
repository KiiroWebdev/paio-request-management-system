<?php

namespace App\Http\Controllers;
use App\Models\ArchivedDocument;
use App\Models\ArchivedDocumentActivity;
use App\Models\DocumentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ArchivedDocumentController extends Controller
{
   

    /**
     * Display archived documents with search and filters.
     */
    public function index(Request $request): View
    {
        $documents = ArchivedDocument::with('uploadedBy')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('subject', 'like', "%{$search}%")
                        ->orWhere('document_number', 'like', "%{$search}%")
                        ->orWhere('originating_office', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('document_type'), function ($query) use ($request) {
                $query->where('document_type', $request->document_type);
            })
            ->when($request->filled('year'), function ($query) use ($request) {
                $query->whereYear('document_date', $request->year);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $documentTypes = DocumentCategory::where('is_active', true)
    ->orderBy('name')
    ->pluck('name');

        return view('document-archives.index', compact(
            'documents',
            'documentTypes'
        ));
    }

    /**
     * Show the upload form.
     */
    public function create(): View
    {
        $documentTypes = DocumentCategory::where('is_active', true)
    ->orderBy('name')
    ->pluck('name');

        return view('document-archives.create', compact('documentTypes'));
    }

    /**
     * Store a newly uploaded document.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
           'document_type' => [
    'required',
    'string',
    'exists:document_categories,name',
    function ($attribute, $value, $fail) {
        $exists = DocumentCategory::where('name', $value)
            ->where('is_active', true)
            ->exists();

        if (! $exists) {
            $fail('Please select a valid active document category.');
        }
    },
],
            'document_number' => ['nullable', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'document_date' => ['nullable', 'date'],
            'originating_office' => ['nullable', 'string', 'max:255'],
            'recipient' => ['nullable', 'string', 'max:255'],
            'remarks' => ['nullable', 'string'],
            'file' => [
                'required',
                'file',
                'mimes:pdf',
                'mimetypes:application/pdf',
                'max:20480',
            ],
        ]);

        $file = $request->file('file');

        $filePath = $file->storeAs(
            'document-archives/' . now()->format('Y'),
            Str::uuid() . '.pdf',
            'local'
        );

        if (! $filePath) {
            throw ValidationException::withMessages([
                'file' => 'The PDF could not be saved. Please try again.',
            ]);
        }

        try {
            $document = ArchivedDocument::create([
                'document_type' => $validated['document_type'],
                'document_number' => $validated['document_number'] ?? null,
                'subject' => $validated['subject'],
                'document_date' => $validated['document_date'] ?? null,
                'originating_office' => $validated['originating_office'] ?? null,
                'recipient' => $validated['recipient'] ?? null,
                'remarks' => $validated['remarks'] ?? null,
                'file_path' => $filePath,
                'original_file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'uploaded_by' => $request->user()->id,
            ]);

            $this->logActivity(
                $request,
                $document,
                'Uploaded',
                'Uploaded a new archived document.'
            );
        } catch (\Throwable $exception) {
            Storage::disk('local')->delete($filePath);

            throw $exception;
        }

        return redirect()
            ->route('document-archives.show', $document)
            ->with('success', 'Document archived successfully.');
    }

    /**
     * Display document details.
     */
    public function show(
        Request $request,
        ArchivedDocument $archivedDocument
    ): View {
        $archivedDocument->load([
            'uploadedBy',
            'updatedBy',
            'activities.user',
        ]);

        $this->logActivity(
            $request,
            $archivedDocument,
            'Viewed',
            'Viewed archived document details.'
        );

        return view('document-archives.show', compact('archivedDocument'));
    }

    /**
     * Show the edit form.
     */
    public function edit(ArchivedDocument $archivedDocument): View
    {
       $documentTypes = DocumentCategory::where('is_active', true)
    ->orWhere('name', $archivedDocument->document_type)
    ->orderBy('name')
    ->pluck('name');

        return view('document-archives.edit', compact(
            'archivedDocument',
            'documentTypes'
        ));
    }

    /**
     * Update document details and optionally replace its PDF.
     */
    public function update(
        Request $request,
        ArchivedDocument $archivedDocument
    ) {
        $validated = $request->validate([
            'document_type' => [
    'required',
    'string',
    function ($attribute, $value, $fail) use ($archivedDocument) {
        $exists = DocumentCategory::where('name', $value)
            ->where(function ($query) use ($archivedDocument) {
                $query->where('is_active', true)
                    ->orWhere('name', $archivedDocument->document_type);
            })
            ->exists();

        if (! $exists) {
            $fail('Please select a valid document category.');
        }
    },
],
            'document_number' => ['nullable', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'document_date' => ['nullable', 'date'],
            'originating_office' => ['nullable', 'string', 'max:255'],
            'recipient' => ['nullable', 'string', 'max:255'],
            'remarks' => ['nullable', 'string'],
            'file' => [
                'nullable',
                'file',
                'mimes:pdf',
                'mimetypes:application/pdf',
                'max:20480',
            ],
        ]);

        $oldFilePath = $archivedDocument->file_path;
        $newFilePath = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $newFilePath = $file->storeAs(
                'document-archives/' . now()->format('Y'),
                Str::uuid() . '.pdf',
                'local'
            );

            if (! $newFilePath) {
                throw ValidationException::withMessages([
                    'file' => 'The replacement PDF could not be saved.',
                ]);
            }
        }

        $metadata = [
            'document_type' => $validated['document_type'],
            'document_number' => $validated['document_number'] ?? null,
            'subject' => $validated['subject'],
            'document_date' => $validated['document_date'] ?? null,
            'originating_office' => $validated['originating_office'] ?? null,
            'recipient' => $validated['recipient'] ?? null,
            'remarks' => $validated['remarks'] ?? null,
            'updated_by' => $request->user()->id,
        ];

        if ($newFilePath) {
            $metadata['file_path'] = $newFilePath;
            $metadata['original_file_name'] = $file->getClientOriginalName();
            $metadata['file_size'] = $file->getSize();
        }

        try {
            $archivedDocument->update($metadata);

            $this->logActivity(
                $request,
                $archivedDocument,
                $newFilePath ? 'PDF Replaced' : 'Updated',
                $newFilePath
                    ? 'Updated document information and replaced the PDF.'
                    : 'Updated document information.'
            );
        } catch (\Throwable $exception) {
            if ($newFilePath) {
                Storage::disk('local')->delete($newFilePath);
            }

            throw $exception;
        }

        // Delete the old file only after the database update succeeds.
        if ($newFilePath) {
            Storage::disk('local')->delete($oldFilePath);
        }

        return redirect()
            ->route('document-archives.show', $archivedDocument)
            ->with('success', 'Archived document updated successfully.');
    }

    /**
     * Securely preview the PDF.
     */
    public function preview(
        Request $request,
        ArchivedDocument $archivedDocument
    ) {
        $disk = Storage::disk('local');

        abort_unless($disk->exists($archivedDocument->file_path), 404);

        $this->logActivity(
            $request,
            $archivedDocument,
            'Viewed PDF',
            'Previewed the archived PDF file.'
        );

        return $disk->response(
            $archivedDocument->file_path,
            $archivedDocument->original_file_name,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' .
                    addcslashes(
                        basename($archivedDocument->original_file_name),
                        '"\\'
                    ) . '"',
                'X-Content-Type-Options' => 'nosniff',
                'Cache-Control' => 'private, no-store',
            ]
        );
    }

    /**
     * Securely download the PDF.
     */
    public function download(
        Request $request,
        ArchivedDocument $archivedDocument
    ): StreamedResponse {
        $disk = Storage::disk('local');

        abort_unless($disk->exists($archivedDocument->file_path), 404);

        $this->logActivity(
            $request,
            $archivedDocument,
            'Downloaded',
            'Downloaded the archived PDF file.'
        );

        return $disk->download(
            $archivedDocument->file_path,
            $archivedDocument->original_file_name,
            [
                'X-Content-Type-Options' => 'nosniff',
                'Cache-Control' => 'private, no-store',
            ]
        );
    }

    /**
     * Soft-delete an archived document.
     */
    public function destroy(
        Request $request,
        ArchivedDocument $archivedDocument
    ) {
        $this->logActivity(
            $request,
            $archivedDocument,
            'Deleted',
            'Soft-deleted an archived document.'
        );

        $archivedDocument->delete();

        // The private PDF is retained for recovery purposes.

        return redirect()
            ->route('document-archives.index')
            ->with('success', 'Document moved to deleted archives.');
    }


public function trashed(): View
{
    $documents = ArchivedDocument::onlyTrashed()
        ->with('uploadedBy')
        ->latest('deleted_at')
        ->paginate(15)
        ->withQueryString();

    return view('document-archives.trashed', compact('documents'));
}

public function restore(Request $request, int $id)
{
    $document = ArchivedDocument::onlyTrashed()->findOrFail($id);

    $document->restore();

    $this->logActivity(
        $request,
        $document,
        'Restored',
        'Restored an archived document from the Recycle Bin.'
    );

    return redirect()
        ->route('document-archives.show', $document)
        ->with('success', 'Document restored successfully.');
}


    /**
     * Record an archive activity.
     */
    private function logActivity(
        Request $request,
        ArchivedDocument $document,
        string $action,
        string $description
    ): void {
        ArchivedDocumentActivity::create([
            'archived_document_id' => $document->id,
            'user_id' => $request->user()->id,
            'action' => $action,
            'description' => $description,
            'ip_address' => $request->ip(),
        ]);
    }
}