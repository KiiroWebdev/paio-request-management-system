<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\DocumentRequest;
use Illuminate\Http\Request;

class RequestApprovalController extends Controller
{

public function index(Request $request)
{
    $filter = $request->query('status', 'Pending');

    $allowedFilters = [
        'All',
        'Pending',
        'Approved',
        'Declined',
        'Cancelled',
    ];

    if (!in_array($filter, $allowedFilters)) {
        $filter = 'Pending';
    }

    $query = DocumentRequest::with([
        'createdBy',
        'services',
        'organization',
    ]);

    if ($filter !== 'All') {
        $query->where('approval_status', $filter);
    }

  $requests = $query
    ->orderByRaw("
        CASE priority
            WHEN 'Urgent' THEN 1
            WHEN 'High' THEN 2
            WHEN 'Normal' THEN 3
            WHEN 'Low' THEN 4
            ELSE 5
        END
    ")
    ->orderByDesc('created_at')
    ->get();

    $pendingCount = DocumentRequest::where('approval_status', 'Pending')
        ->count();

    $approvedCount = DocumentRequest::where('approval_status', 'Approved')
        ->count();

    $declinedCount = DocumentRequest::where('approval_status', 'Declined')
        ->count();

    $cancelledCount = DocumentRequest::where('approval_status', 'Cancelled')
        ->count();

    return view('request-approvals.index', compact(
        'requests',
        'pendingCount',
        'approvedCount',
        'declinedCount',
        'cancelledCount',
        'filter'
    ));
}
    /**
     * Approve a request.
     */
    public function approve(Request $request, DocumentRequest $documentRequest)
    {
        if ($documentRequest->status !== 'Incoming') {
            return back()->with(
                'error',
                'Only Incoming requests can be approved.'
            );
        }

        if ($documentRequest->approval_status !== 'Pending') {
            return back()->with(
                'error',
                'This request has already been processed.'
            );
        }

        $validated = $request->validate([
            'approval_review_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $documentRequest->update([
            'approval_status' => 'Approved',
            'approval_reviewed_by' => auth()->id(),
            'approval_reviewed_at' => now(),
            'approval_review_note' => $validated['approval_review_note'] ?? null,
        ]);

        ActivityLog::create([
            'request_id' => $documentRequest->id,
            'user_id' => auth()->id(),
            'action' => 'Request Approved',
            'description' => "Request {$documentRequest->control_no} was approved by administrator.",
            'old_value' => 'Pending',
            'new_value' => 'Approved',
            'ip_address' => $request->ip(),
        ]);

        return back()->with(
            'success',
            'Request approved successfully.'
        );
    }

    /**
     * Decline a request.
     */
    public function decline(Request $request, DocumentRequest $documentRequest)
    {
        if ($documentRequest->status !== 'Incoming') {
            return back()->with(
                'error',
                'Only Incoming requests can be declined.'
            );
        }

        if ($documentRequest->approval_status !== 'Pending') {
            return back()->with(
                'error',
                'This request has already been processed.'
            );
        }

        $validated = $request->validate([
            'approval_review_note' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);

        $documentRequest->update([
            'approval_status' => 'Declined',
            'approval_reviewed_by' => auth()->id(),
            'approval_reviewed_at' => now(),
            'approval_review_note' => $validated['approval_review_note'],
        ]);

        ActivityLog::create([
            'request_id' => $documentRequest->id,
            'user_id' => auth()->id(),
            'action' => 'Request Declined',
            'description' => "Request {$documentRequest->control_no} was declined by administrator.",
            'old_value' => 'Pending',
            'new_value' => 'Declined',
            'ip_address' => $request->ip(),
        ]);

        return back()->with(
            'success',
            'Request declined successfully.'
        );
    }

    /**
     * Cancel a request.
     */
    public function cancel(Request $request, DocumentRequest $documentRequest)
    {
        if ($documentRequest->status !== 'Incoming') {
            return back()->with(
                'error',
                'Only Incoming requests can be cancelled.'
            );
        }

        if ($documentRequest->approval_status !== 'Pending') {
            return back()->with(
                'error',
                'This request has already been processed.'
            );
        }

        $validated = $request->validate([
            'approval_review_note' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);

        $documentRequest->update([
            'approval_status' => 'Cancelled',
            'approval_reviewed_by' => auth()->id(),
            'approval_reviewed_at' => now(),
            'approval_review_note' => $validated['approval_review_note'],
        ]);

        ActivityLog::create([
            'request_id' => $documentRequest->id,
            'user_id' => auth()->id(),
            'action' => 'Request Cancelled',
            'description' => "Request {$documentRequest->control_no} was cancelled by administrator.",
            'old_value' => 'Pending',
            'new_value' => 'Cancelled',
            'ip_address' => $request->ip(),
        ]);

        return back()->with(
            'success',
            'Request cancelled successfully.'
        );
    }

    /**
     * Restore a declined/cancelled request back to Pending Approval.
     */
    public function restoreForApproval(
        Request $request,
        DocumentRequest $documentRequest
    ) {
        if ($documentRequest->status !== 'Incoming') {
            return back()->with(
                'error',
                'Only Incoming requests can be restored for approval.'
            );
        }

        if (!in_array(
            $documentRequest->approval_status,
            ['Declined', 'Cancelled']
        )) {
            return back()->with(
                'error',
                'Only declined or cancelled requests can be restored for approval.'
            );
        }

        $validated = $request->validate([
            'approval_review_note' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);

        $oldApprovalStatus = $documentRequest->approval_status;

        $documentRequest->update([
            'approval_status' => 'Pending',
            'approval_reviewed_by' => auth()->id(),
            'approval_reviewed_at' => now(),
            'approval_review_note' => $validated['approval_review_note'],
        ]);

        ActivityLog::create([
            'request_id' => $documentRequest->id,
            'user_id' => auth()->id(),
            'action' => 'Request Restored for Approval',
            'description' => "Request {$documentRequest->control_no} was restored for approval by administrator.",
            'old_value' => $oldApprovalStatus,
            'new_value' => 'Pending',
            'ip_address' => $request->ip(),
        ]);

        return back()->with(
            'success',
            'Request restored for approval successfully.'
        );
    }
}