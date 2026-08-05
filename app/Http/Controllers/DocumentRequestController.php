<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequest;
use App\Models\Service;
use Illuminate\Http\Request;

class DocumentRequestController extends Controller
{
    

    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $requests = DocumentRequest::with([
        'organization',
        'contact',
        'services',
    ])
    ->latest()
    ->get();

    return view('requests.index', compact('requests'));
}
    /**
 * Show the form for creating a new resource.
 */
public function create()
{
    return redirect()->route('request-wizard.services');
}
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(DocumentRequest $documentRequest)
{
    $documentRequest->load([
    'organization',
    'contact',
    'services',
    'answers.field.fieldType',
    'answers.field.options',
]);

    return view(
    'requests.show',
    compact('documentRequest')
);
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentRequest $documentRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DocumentRequest $documentRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentRequest $documentRequest)
    {
        //
    }
}