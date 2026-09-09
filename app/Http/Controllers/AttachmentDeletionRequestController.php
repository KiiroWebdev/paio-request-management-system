<?php


namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\AttachmentDeletionRequest;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AttachmentDeletionRequestController extends Controller
{
    public function index()
    {
       $requests = AttachmentDeletionRequest::with([
    'attachment',
    'requester',
    'reviewer',
])
        ->latest()
        ->get();

        return view(
            'attachment-deletion-requests.index',
            compact('requests')
        );
    }

    public function store(Request $request, Attachment $attachment)
    {
        $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $existingRequest = AttachmentDeletionRequest::where('attachment_id', $attachment->id)
            ->where('status', 'Pending')
            ->exists();

        if ($existingRequest) {
            return back()->with(
                'error',
                'A deletion request for this file is already pending.'
            );
        }

        AttachmentDeletionRequest::create([
    'attachment_id' => $attachment->id,
    'file_name' => $attachment->file_name,
    'requested_by' => auth()->id(),
    'reason' => $request->reason,
    'status' => 'Pending',
]);

        return back()->with(
            'success',
            'File deletion request submitted successfully.'
        );
    }
   public function approve(AttachmentDeletionRequest $deletionRequest)
{
    // Make sure the request is still pending
    if ($deletionRequest->status !== 'Pending') {
        return back()->with(
            'error',
            'This deletion request has already been processed.'
        );
    }

    $attachment = $deletionRequest->attachment;

    // Make sure the attachment still exists
    if (!$attachment) {

        $deletionRequest->update([
            'status' => 'Approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_note' => 'Attachment was already missing.',
        ]);

        return back()->with(
            'error',
            'The attachment no longer exists.'
        );
    }

    // Capture values before deleting the attachment
    $fileName = $attachment->file_name;
    $filePath = $attachment->file_path;
    $requestId = $attachment->request_id;

    DB::transaction(function () use (
        $deletionRequest,
        $attachment,
        $fileName,
        $filePath,
        $requestId
    ) {

        // Mark deletion request as approved FIRST
        $deletionRequest->update([
            'status' => 'Approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_note' => 'Deletion approved by administrator.',
        ]);

        // Delete physical file
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }

        // Delete attachment database record
        // attachment_id in deletion request will become NULL
        $attachment->delete();

        // Record activity
        ActivityLog::create([
            'request_id' => $requestId,
            'user_id' => auth()->id(),
            'action' => 'Attachment Deletion Approved',
            'description' => "Attachment deletion approved: {$fileName}.",
            'old_value' => $fileName,
            'new_value' => 'Deleted',
            'ip_address' => request()->ip(),
        ]);
    });

    return back()->with(
        'success',
        'Attachment deletion request approved successfully.'
    );
}

public function reject(Request $request, AttachmentDeletionRequest $deletionRequest)
{
    $validated = $request->validate([
        'review_note' => ['required', 'string', 'max:1000'],
    ]);

    if ($deletionRequest->status !== 'Pending') {
        return back()->with(
            'error',
            'This deletion request has already been processed.'
        );
    }

    $deletionRequest->update([
        'status' => 'Rejected',
        'reviewed_by' => auth()->id(),
        'review_note' => $validated['review_note'],
        'reviewed_at' => now(),
    ]);

    ActivityLog::create([
        'request_id' => $deletionRequest->attachment?->request_id,
        'user_id' => auth()->id(),
        'action' => 'Attachment Deletion Rejected',
        'description' => "Attachment deletion request rejected: {$deletionRequest->file_name}.",
        'old_value' => 'Pending',
        'new_value' => 'Rejected',
        'ip_address' => request()->ip(),
    ]);

    return back()->with(
        'success',
        'Attachment deletion request rejected successfully.'
    );
}
}