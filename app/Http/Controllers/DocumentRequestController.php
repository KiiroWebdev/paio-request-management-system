<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Services\RequestWorkflowService;
use App\Http\Requests\UpdateDocumentRequestRequest;
use App\Actions\Requests\UpdateRequestAnswers;

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

    $pageTitle = match ($request->status) {
    'Incoming' => 'Incoming Requests',
    'Ongoing' => 'Ongoing Requests',
    'Completed' => 'Completed Requests',
    'Archived' => 'Archived Requests',
    default => 'Request Management',
};

return view('requests.index', [

    'requests' => $requests,

    'organizations' => \App\Models\Organization::orderBy('name')->get(),

    'services' => \App\Models\Service::orderBy('name')->get(),

    'pageTitle' => $pageTitle,

]);
}
public function incoming(Request $request)
{
    $request->merge([
        'status' => 'Incoming',
    ]);

    return $this->index($request);
}

public function ongoing(Request $request)
{
    $request->merge([
        'status' => 'Ongoing',
    ]);

    return $this->index($request);
}

public function completed(Request $request)
{
    $request->merge([
        'status' => 'Completed',
    ]);

    return $this->index($request);
}

public function archiveIndex(Request $request)
{
    $request->merge([
        'status' => 'Archived',
    ]);

    return $this->index($request);
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

public function print(DocumentRequest $documentRequest)
{
    $documentRequest->load([
        'organization',
        'contact',
        'services.fields.fieldType',
        'services.fields.options',
        'assignedTo',
        'answers.field.fieldType',
        'answers.field.options',
    ]);

    return view(
        'requests.print',
        compact('documentRequest')
    );
}
    /**
     * Show the form for editing the specified resource.
     */
 public function edit(DocumentRequest $documentRequest)
{
    
    $documentRequest->load([
        'organization',
        'contact',
        'services.fields.fieldType',
        'services.fields.options',
        'answers.field.fieldType',
        'answers.field.options',
    ]);

    $organizations = \App\Models\Organization::where('is_active', true)
        ->orderBy('name')
        ->get();

    $contacts = \App\Models\Contact::where('is_active', true)
        ->orderBy('last_name')
        ->get();

    // ...

    $documentRequest->load([
        'organization',
        'contact',
        'services.fields.fieldType',
        'services.fields.options',
        'answers.field.fieldType',
        'answers.field.options',
    ]);

    $organizations = \App\Models\Organization::where('is_active', true)
        ->orderBy('name')
        ->get();

    $contacts = \App\Models\Contact::where('is_active', true)
        ->orderBy('last_name')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Existing Dynamic Answers
    |--------------------------------------------------------------------------
    */

    $answers = $documentRequest->answers
        ->mapWithKeys(function ($answer) {

            $value = $answer->value;

            if ($answer->field?->fieldType?->key === 'checkbox') {
                $value = json_decode($value, true) ?? [];
            }

            return [
                $answer->service_field_id => $value,
            ];
        })
        ->toArray();

    return view(
        'requests.edit',
        compact(
            'documentRequest',
            'organizations',
            'contacts',
            'answers'
        )
    );
}

    /**
    

 * Update the specified resource in storage.
 */
public function update(
    UpdateDocumentRequestRequest $request,
    DocumentRequest $documentRequest,
    UpdateRequestAnswers $updateRequestAnswers
) {
    $documentRequest->load([
        'services.fields.fieldType',
        'answers',
    ]);

    $data = $request->validated();

    $fields = $documentRequest->services
        ->flatMap(
            fn ($service) =>
                $service->fields->where('active', true)
        );

    DB::transaction(function () use (
        $documentRequest,
        $data,
        $fields,
        $updateRequestAnswers
    ) {

        $documentRequest->update([

            'organization_id' => $data['organization_id'],

            'contact_id' => $data['contact_id'],

            'title' => $data['title'],

            'subject' => $data['subject'],

            'description' => $data['description'] ?? null,

            'date_received' => $data['date_received'],

            'request_type' => $data['request_type'],

            'priority' => $data['priority'],

        ]);

        $updateRequestAnswers->execute(
            $documentRequest,
            $data['answers'] ?? [],
            $fields
        );

    });

    return redirect()
        ->route('requests.show', $documentRequest)
        ->with(
            'success',
            'Request updated successfully.'
        );
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