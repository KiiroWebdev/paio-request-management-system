<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\DocumentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    /**
     * Store a newly uploaded attachment.
     */
    public function store(
        Request $request,
        DocumentRequest $documentRequest
    ) {
        $validated = $request->validate([
            'attachment' => [
                'required',
                'file',
                'max:20480',
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,webp,mp4,mov',
            ],

            'attachment_type' => [
                'required',
                'in:Request Letter,Program,Layout,Memorandum,Photo,Video,Document,Others',
            ],
        ]);

        $file = $validated['attachment'];

        $path = $file->store(
            'attachments/' . $documentRequest->id,
            'public'
        );

        $attachment = Attachment::create([
    'request_id' => $documentRequest->id,
    'file_name' => $file->getClientOriginalName(),
    'file_path' => $path,
    'file_type' => $file->getClientOriginalExtension(),
    'file_size' => $file->getSize(),
    'attachment_type' => $validated['attachment_type'],
    'uploaded_by' => Auth::id(),
]);

\App\Models\ActivityLog::create([
    'request_id' => $documentRequest->id,
    'user_id' => Auth::id(),
    'action' => 'Attachment Uploaded',
    'description' => "Attachment uploaded: {$attachment->file_name}.",
    'old_value' => null,
    'new_value' => $attachment->file_name,
    'ip_address' => request()->ip(),
]);

        return back()->with(
            'success',
            'Attachment uploaded successfully.'
        );
    }
    /**
 * View an attachment in the browser.
 */
public function show(Attachment $attachment)
{
    abort_unless(
        Storage::disk('public')->exists($attachment->file_path),
        404
    );

    return Storage::disk('public')->response(
        $attachment->file_path,
        $attachment->file_name,
        [
            'Content-Type' => Storage::disk('public')
                ->mimeType($attachment->file_path),
        ]
    );
}

/**
 * Download an attachment.
 */
public function download(Attachment $attachment)
{
    abort_unless(
        Storage::disk('public')->exists($attachment->file_path),
        404
    );

    return Storage::disk('public')->download(
        $attachment->file_path,
        $attachment->file_name
    );
}

/**
 * Delete an attachment.
 */
public function destroy(Attachment $attachment)
{
    $fileName = $attachment->file_name;
    $request = $attachment->request;

    // Delete physical file
    if (Storage::disk('public')->exists($attachment->file_path)) {
        Storage::disk('public')->delete($attachment->file_path);
    }

    // Delete database record
    $attachment->delete();

    // Record activity
    \App\Models\ActivityLog::create([
        'request_id' => $request->id,
        'user_id' => Auth::id(),
        'action' => 'Attachment Deleted',
        'description' => "Attachment deleted: {$fileName}.",
        'old_value' => $fileName,
        'new_value' => null,
        'ip_address' => request()->ip(),
    ]);

    return back()->with(
        'success',
        'Attachment deleted successfully.'
    );
}
}