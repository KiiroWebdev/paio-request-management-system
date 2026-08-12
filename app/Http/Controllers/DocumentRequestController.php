<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequest;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Services\RequestWorkflowService;

class DocumentRequestController extends Controller
{
    protected RequestWorkflowService $workflowService;

    public function __construct(RequestWorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $query = DocumentRequest::with([
        'organization',
        'contact',
        'services',
    ]);

    // Search
    if ($request->filled('search')) {

        $query->where(function ($q) use ($request) {

            $q->where('control_no', 'like', '%' . $request->search . '%')
              ->orWhere('subject', 'like', '%' . $request->search . '%');

        });

    }

    // Organization Filter
    if ($request->filled('organization')) {

        $query->where('organization_id', $request->organization);

    }

    // Status Filter
    if ($request->filled('status')) {

        $query->where('status', $request->status);

    }

    // Priority Filter
    if ($request->filled('priority')) {

        $query->where('priority', $request->priority);

    }

    // Service Filter
    if ($request->filled('service')) {

        $query->whereHas('services', function ($q) use ($request) {

            $q->where('services.id', $request->service);

        });

    }

    $requests = $query
        ->latest()
        ->paginate(15)
        ->withQueryString();

    return view('requests.index', [

        'requests' => $requests,

        'organizations' => \App\Models\Organization::orderBy('name')->get(),

        'services' => \App\Models\Service::orderBy('name')->get(),

    ]);
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
        'assignedTo',
        'answers.field.fieldType',
        'answers.field.options',
    ]);

    $users = \App\Models\User::orderBy('name')->get();

    return view(
        'requests.show',
        compact(
            'documentRequest',
            'users'
        )
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

    public function assign(
    Request $request,
    DocumentRequest $documentRequest
)
{
    $request->validate([

        'assigned_to' => [
            'required',
            'exists:users,id'
        ]

    ]);

    $this->workflowService->assign(
    $documentRequest,
    (int) $request->assigned_to
);

    return back()->with(

        'success',

        'Request assigned successfully.'

    );
}

    public function start(
    DocumentRequest $documentRequest
)
{
    $this->workflowService->start($documentRequest);

    return back()->with(

        'success',

        'Request started successfully.'

    );
}

    public function complete(
    DocumentRequest $documentRequest
)
{
    $this->workflowService->complete($documentRequest);

    return back()->with(

        'success',

        'Request completed successfully.'

    );
}

    public function archive(
    DocumentRequest $documentRequest
)
{
    $this->workflowService->archive($documentRequest);

    return back()->with(

        'success',

        'Request archived successfully.'

    );
}

}