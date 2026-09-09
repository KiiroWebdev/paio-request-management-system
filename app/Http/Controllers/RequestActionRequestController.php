<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequest;
use App\Models\RequestActionRequest;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class RequestActionRequestController extends Controller
{
    /**
     * Display all archive/restore requests.
     */
    public function index()
    {
        $requests = RequestActionRequest::with([
            'documentRequest',
            'requester',
            'reviewer',
        ])
        ->latest()
        ->get();

        return view(
            'request-action-requests.index',
            compact('requests')
        );
    }

    /**
     * Submit an archive/restore request.
     */
    public function store(Request $request, DocumentRequest $documentRequest)
    {
        $validated = $request->validate([
            'action' => [
                'required',
                'in:Archive,Restore',
            ],

            'reason' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);

        // Prevent duplicate pending requests for the same action.
        $existingRequest = RequestActionRequest::where(
            'document_request_id',
            $documentRequest->id
        )
        ->where('action', $validated['action'])
        ->where('status', 'Pending')
        ->exists();

        if ($existingRequest) {
            return back()->with(
                'error',
                "A {$validated['action']} request for this document is already pending."
            );
        }

        // Validate that the requested action makes sense
        // based on the current request status.
        if (
            $validated['action'] === 'Archive' &&
            $documentRequest->status === 'Archived'
        ) {
            return back()->with(
                'error',
                'This document request is already archived.'
            );
        }

        if (
            $validated['action'] === 'Restore' &&
            $documentRequest->status !== 'Archived'
        ) {
            return back()->with(
                'error',
                'Only archived document requests can be restored.'
            );
        }

        $actionRequest = RequestActionRequest::create([
            'document_request_id' => $documentRequest->id,
            'requested_by' => auth()->id(),
            'action' => $validated['action'],
            'reason' => $validated['reason'],
            'status' => 'Pending',
        ]);

        ActivityLog::create([
            'request_id' => $documentRequest->id,
            'user_id' => auth()->id(),
            'action' => "{$validated['action']} Requested",
            'description' =>
                "{$validated['action']} requested for document request "
                . "{$documentRequest->control_no}.",
            'old_value' => $documentRequest->status,
            'new_value' => 'Pending',
            'ip_address' => request()->ip(),
        ]);

        return back()->with(
            'success',
            "{$validated['action']} request submitted successfully."
        );
    }

    /**
     * Approve an archive/restore request.
     */
    public function approve(RequestActionRequest $actionRequest)
    {
        if ($actionRequest->status !== 'Pending') {
            return back()->with(
                'error',
                'This request has already been processed.'
            );
        }

        $documentRequest = $actionRequest->documentRequest;

        if (!$documentRequest) {
            return back()->with(
                'error',
                'The associated document request no longer exists.'
            );
        }

        $action = $actionRequest->action;
        $oldStatus = $documentRequest->status;

        // Safety check before performing the action.
        if (
            $action === 'Archive' &&
            $documentRequest->status === 'Archived'
        ) {
            return back()->with(
                'error',
                'This document request is already archived.'
            );
        }

        if (
            $action === 'Restore' &&
            $documentRequest->status !== 'Archived'
        ) {
            return back()->with(
                'error',
                'This document request is no longer archived.'
            );
        }

        if ($action === 'Archive') {

            $documentRequest->update([
                'status' => 'Archived',
                'archived_at' => now(),
            ]);

        } elseif ($action === 'Restore') {

            $documentRequest->update([
                'status' => 'Completed',
                'archived_at' => null,
            ]);
        }

        $actionRequest->update([
            'status' => 'Approved',
            'reviewed_by' => auth()->id(),
            'review_note' => "{$action} approved by administrator.",
            'reviewed_at' => now(),
        ]);

        ActivityLog::create([
            'request_id' => $documentRequest->id,
            'user_id' => auth()->id(),
            'action' => "{$action} Approved",
            'description' =>
                "{$action} approved for document request "
                . "{$documentRequest->control_no}.",
            'old_value' => $oldStatus,
            'new_value' => $documentRequest->status,
            'ip_address' => request()->ip(),
        ]);

        return back()->with(
            'success',
            "{$action} request approved successfully."
        );
    }

    /**
     * Reject an archive/restore request.
     */
    public function reject(
        Request $request,
        RequestActionRequest $actionRequest
    ) {
        $validated = $request->validate([
            'review_note' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);

        if ($actionRequest->status !== 'Pending') {
            return back()->with(
                'error',
                'This request has already been processed.'
            );
        }

        $actionRequest->update([
            'status' => 'Rejected',
            'reviewed_by' => auth()->id(),
            'review_note' => $validated['review_note'],
            'reviewed_at' => now(),
        ]);

        ActivityLog::create([
            'request_id' => $actionRequest->document_request_id,
            'user_id' => auth()->id(),
            'action' => "{$actionRequest->action} Rejected",
            'description' =>
                "{$actionRequest->action} request rejected for document request "
                . "{$actionRequest->documentRequest?->control_no}.",
            'old_value' => 'Pending',
            'new_value' => 'Rejected',
            'ip_address' => request()->ip(),
        ]);

        return back()->with(
            'success',
            "{$actionRequest->action} request rejected successfully."
        );
    }
}
