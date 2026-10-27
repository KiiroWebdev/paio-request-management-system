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
        $staffUsers = User::where('role', 'Staff')
    ->where('is_active', true)
    ->orderBy('name')
    ->get(['id', 'name']);

       
        return view('requests.kanban', compact('columns', 'staffUsers'));
    }

    public function move(
        Request $request,
        DocumentRequest $documentRequest
    ) {
        $validated = $request->validate([
            'destination' => [
                'required',
                'in:Assigned,Ongoing,Completed',
            ],
            'assigned_to' => [
                'required_if:destination,Assigned',
                'nullable',
                'integer',
                'exists:users,id',
            ],
        ]);

        if ($documentRequest->approval_status !== 'Approved') {
            return response()->json([
                'message' => 'This request must be approved before it can be moved.',
            ], 422);
        }

        $destination = $validated['destination'];

        try {
            if ($destination === 'Assigned') {
                if ($documentRequest->status !== 'Incoming') {
                    return response()->json([
                        'message' => 'Only Incoming requests can be assigned.',
                    ], 422);
                }

                app(\App\Services\RequestWorkflowService::class)->assign(
                    $documentRequest,
                    (int) $validated['assigned_to']
                );
            } elseif ($destination === 'Ongoing') {
                if ($documentRequest->status !== 'Assigned') {
                    return response()->json([
                        'message' => 'Only Assigned requests can move to Ongoing.',
                    ], 422);
                }

                app(\App\Services\RequestWorkflowService::class)
                    ->start($documentRequest);
            } elseif ($destination === 'Completed') {
                if ($documentRequest->status !== 'Ongoing') {
                    return response()->json([
                        'message' => 'Only Ongoing requests can be completed.',
                    ], 422);
                }

                app(\App\Services\RequestWorkflowService::class)
                    ->complete($documentRequest);
            }

            return response()->json([
                'message' => 'Request moved successfully.',
                'status' => $documentRequest->fresh()->status,
            ]);
        } catch (\Illuminate\Validation\ValidationException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }
    }
}
