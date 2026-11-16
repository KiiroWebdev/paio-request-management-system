<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequest;
use Illuminate\Http\Request;
use App\Models\User;

class RequestKanbanController extends Controller
{

public function index(Request $request)
{
    $requests = DocumentRequest::with([
        'organization',
        'contact',
        'assignedTo',
        'assignedPersonnel',
        'services',
    ])
        ->where('status', '!=', 'Archived')
        ->orderByDesc('created_at')
        ->get();

    $columns = [
        'For Approval' => $requests->filter(
            fn ($item) =>
                $item->approval_status === 'Pending'
        ),

        'Incoming' => $requests->filter(
            fn ($item) =>
                $item->status === 'Incoming'
                && $item->approval_status === 'Approved'
        ),

        'Assigned' => $requests->filter(
            fn ($item) =>
                $item->status === 'Assigned'
        ),

        'Ongoing' => $requests->filter(
            fn ($item) =>
                $item->status === 'Ongoing'
        ),

        'Completed' => $requests->filter(
            fn ($item) =>
                $item->status === 'Completed'
        ),

        'Declined' => $requests->filter(
            fn ($item) =>
                $item->approval_status === 'Declined'
        ),

        'Cancelled' => $requests->filter(
            fn ($item) =>
                $item->approval_status === 'Cancelled'
        ),
    ];

    $personnels = \App\Models\Personnel::where('is_active', true)
        ->orderBy('name')
        ->get(['id', 'name', 'position', 'department']);

    return view('requests.kanban', compact('columns', 'personnels'));
}

 
public function move(
    Request $request,
    DocumentRequest $documentRequest
) {
    $user = $request->user();

    if (
        ! $user
        || (! $user->isAdmin() && ! $user->isStaff())
    ) {
        return response()->json([
            'message' => 'You do not have permission to move requests.',
        ], 403);
    }

   
$validated = $request->validate([
    'destination' => [
        'required',
        'in:Assigned,Ongoing,Completed',
    ],

    'personnel_ids' => [
        'required_if:destination,Assigned',
        'array',
        'min:1',
    ],

    'personnel_ids.*' => [
        'required',
        'integer',
        'distinct',
        'exists:personnels,id',
    ],
]);


    if ($documentRequest->approval_status !== 'Approved') {
        return response()->json([
            'message' => 'This request must be approved before it can be moved.',
        ], 422);
    }

    $destination = $validated['destination'];
    $workflow = app(\App\Services\RequestWorkflowService::class);

    try {
        if ($destination === 'Assigned') {
            if (! $user->isAdmin()) {
                return response()->json([
                    'message' => 'Only administrators can assign requests.',
                ], 403);
            }

            if ($documentRequest->status !== 'Incoming') {
                return response()->json([
                    'message' => 'Only Incoming requests can be assigned.',
                ], 422);
            }

            $workflow->assignPersonnel(
    $documentRequest,
    $validated['personnel_ids']
);

        } elseif ($destination === 'Ongoing') {
            if ($documentRequest->status !== 'Assigned') {
                return response()->json([
                    'message' => 'Only Assigned requests can be started.',
                ], 422);
            }

            $workflow->start($documentRequest);

        } elseif ($destination === 'Completed') {
            if ($documentRequest->status !== 'Ongoing') {
                return response()->json([
                    'message' => 'Only Ongoing requests can be completed.',
                ], 422);
            }

            $workflow->complete($documentRequest);
        }

        return response()->json([
            'message' => 'Request moved successfully.',
            'status' => $documentRequest->fresh()->status,
        ]);

    } catch (\Illuminate\Validation\ValidationException $exception) {
        return response()->json([
            'message' => $exception->getMessage(),
        ], 422);

    } catch (\Symfony\Component\HttpKernel\Exception\HttpExceptionInterface $exception) {
        return response()->json([
            'message' => $exception->getMessage() ?: 'You are not authorized to perform this action.',
        ], $exception->getStatusCode());
    }
}

  
    
/**
 * Handle approval decisions made from the Kanban board.
 */
public function decide(
    Request $request,
    DocumentRequest $documentRequest
) {
    $user = $request->user();

    // Only administrators can process approval decisions.
    if (!$user || !$user->isAdmin()) {
        return response()->json([
            'message' => 'Only administrators can process approval decisions.',
        ], 403);
    }

    // Validate the decision and any required reason.
    $validated = $request->validate([
    'decision' => ['required', 'in:Approve,Decline,Cancel'],
    'reason' => [
        'required_if:decision,Decline,Cancel',
        'nullable',
        'string',
        'max:1000',
    ],
    'approval_review_note' => [
        'nullable',
        'string',
        'max:1000',
    ],
]);

    // Make sure the request is still awaiting approval.
    if (
        $documentRequest->approval_status !== 'Pending'
        || $documentRequest->status !== 'Incoming'
    ) {
        return response()->json([
            'message' => 'This request is no longer awaiting approval.',
        ], 422);
    }

    $decision = $validated['decision'];
    $reason = trim($validated['reason'] ?? '');

    if (
        in_array($decision, ['Decline', 'Cancel'], true)
        && $reason === ''
    ) {
        return response()->json([
            'message' => 'Please provide a reason for this decision.',
        ], 422);
    }

    $oldApprovalStatus = $documentRequest->approval_status;

    if ($decision === 'Approve') {
        $documentRequest->update([
            'status' => 'Incoming',
            'approval_status' => 'Approved',
            'approval_reviewed_by' => $user->id,
            'approval_reviewed_at' => now(),
            'approval_review_note' => $validated['approval_review_note'] ?? null,
            'approval_closed_at' => null,
        ]);

        $action = 'Request Approved';
        $newApprovalStatus = 'Approved';
        $approvalNote = trim($validated['approval_review_note'] ?? '');

$description = "Request {$documentRequest->control_no} was approved from the Kanban board.";

if ($approvalNote !== '') {
    $description .= " Approval Note: {$approvalNote}";
}
    } else {
        $newApprovalStatus = $decision === 'Decline'
            ? 'Declined'
            : 'Cancelled';

        $documentRequest->update([
            'approval_status' => $newApprovalStatus,
            'approval_reviewed_by' => $user->id,
            'approval_reviewed_at' => now(),
            'approval_review_note' => $reason,
            'approval_closed_at' => now(),
        ]);

        $action = $decision === 'Decline'
            ? 'Request Declined'
            : 'Request Cancelled';

        $description = "Request {$documentRequest->control_no} was "
            . strtolower($newApprovalStatus)
            . " from the Kanban board. Reason: {$reason}";
    }

    
\App\Models\ActivityLog::create([
    'request_id' => $documentRequest->id,
    'user_id' => $user->id,
    'action' => $action,
    'description' => $description,
    'old_value' => $oldApprovalStatus,
    'new_value' => $newApprovalStatus,
    'ip_address' => $request->ip(),
]);


    return response()->json([
        'message' => match ($decision) {
            'Approve' => 'Request approved and moved to Incoming.',
            'Decline' => 'Request declined successfully.',
            'Cancel' => 'Request cancelled successfully.',
        },
        'decision' => $newApprovalStatus,
        'status' => $documentRequest->fresh()->status,
    ]);
}


/**
 * Update personnel assigned to an existing request.
 * The request remains in its current status.
 */
public function updateAssignment(
    Request $request,
    DocumentRequest $documentRequest
) {
    $validated = $request->validate([
        'personnel_ids' => [
            'required',
            'array',
            'min:1',
        ],
        'personnel_ids.*' => [
            'required',
            'integer',
            'distinct',
            'exists:personnels,id',
        ],
    ]);

    if (
        $documentRequest->status !== 'Assigned'
        || $documentRequest->approval_status !== 'Approved'
    ) {
        return response()->json([
            'message' => 'Only approved requests in Assigned status can have their assignments edited.',
        ], 422);
    }

    $personnel = \App\Models\Personnel::query()
        ->whereIn('id', $validated['personnel_ids'])
        ->where('is_active', true)
        ->get();

    if (
        $personnel->count() !== count(array_unique($validated['personnel_ids']))
    ) {
        return response()->json([
            'message' => 'All selected personnel must be active.',
        ], 422);
    }

    $oldNames = $documentRequest->assignedPersonnel()
        ->pluck('name')
        ->sort()
        ->values()
        ->all();

    $newNames = $personnel->pluck('name')
        ->sort()
        ->values()
        ->all();

    if ($oldNames === $newNames) {
        return response()->json([
            'message' => 'No assignment changes were made.',
        ]);
    }

    $documentRequest->assignedPersonnel()->sync($personnel->modelKeys());

    $oldDescription = $oldNames
        ? implode(', ', $oldNames)
        : 'None';

    $newDescription = implode(', ', $newNames);

    \App\Models\ActivityLog::create([
        'request_id' => $documentRequest->id,
        'user_id' => auth()->id(),
        'action' => 'Assignment Updated',
        'description' => "Assigned personnel changed from [{$oldDescription}] to [{$newDescription}].",
        'old_status' => $documentRequest->status,
        'new_status' => $documentRequest->status,
    ]);

    return response()->json([
        'message' => 'Assignment updated successfully.',
        'personnel' => $newNames,
    ]);
}


/**
 * Update the progress of an individual service from the Kanban board.
 */
public function updateServiceProgress(
    Request $request,
    DocumentRequest $documentRequest,
    int $service
) {
    $validated = $request->validate([
        'status' => [
            'required',
            'in:Pending,Ongoing,Done',
        ],
    ]);

    $workflow = app(\App\Services\RequestWorkflowService::class);

    $workflow->updateServiceProgress(
        $documentRequest,
        $service,
        $validated['status']
    );

    $documentRequest->refresh();

    return response()->json([
        'message' => 'Service progress updated successfully.',
        'request_status' => $documentRequest->status,
        'services' => $documentRequest->services()
            ->get()
            ->map(fn ($item) => [
                'id' => $item->id,
                'name' => $item->name,
                'status' => $item->pivot->status,
                'completed_at' => $item->pivot->completed_at,
            ]),
    ]);
}


}
