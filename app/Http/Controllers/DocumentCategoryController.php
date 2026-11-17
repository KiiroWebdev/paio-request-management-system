<?php

namespace App\Http\Controllers;

use App\Models\DocumentCategory;
use App\Models\ArchivedDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DocumentCategoryController extends Controller
{
    /**
     * Display all document categories.
     */
    public function index(): View
    {
        $categories = DocumentCategory::orderBy('name')
            ->paginate(15);

        return view('document-categories.index', compact('categories'));
    }

    /**
     * Store a new document category.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:document_categories,name',
            ],
        ]);

        DocumentCategory::create([
            'name' => $validated['name'],
            'is_active' => true,
        ]);

        return redirect()
            ->route('document-archives.categories.index')
            ->with('success', 'Document category added successfully.');
    }

    
/**
 * Update a document category and synchronize existing archived documents.
 */
public function update(Request $request, DocumentCategory $category)
{
    $request->merge([
        'name' => trim((string) $request->input('name')),
    ]);

    $validated = $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
            Rule::unique('document_categories', 'name')
                ->ignore($category->id),
        ],
    ]);

    $oldName = $category->name;
    $newName = $validated['name'];

    DB::transaction(function () use ($category, $oldName, $newName, $request) {

        // Rename the category.
        $category->update([
            'name' => $newName,
        ]);

        // Synchronize all documents, including soft-deleted records.
        ArchivedDocument::withTrashed()
            ->where('document_type', $oldName)
            ->update([
                'document_type' => $newName,
                'updated_by' => $request->user()->id,
                'updated_at' => now(),
            ]);
    });

    return back()->with(
        'success',
        'Document category and associated archived documents updated successfully.'
    );
}

    /**
     * Activate or deactivate a document category.
     */
    public function toggleStatus(DocumentCategory $category)
    {
        $category->update([
            'is_active' => ! $category->is_active,
        ]);

        return back()->with(
            'success',
            'Document category status updated successfully.'
        );
    }
}