<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\DocumentRequest;
use Illuminate\Validation\ValidationException;

class RequestWorkflowService
{
    /**
     * Assign an incoming request to a user.
     */
    public function assign(
        DocumentRequest $request,
        int $userId
    ): void {
        $this->ensureStatus($request, 'Incoming');

        $user = \App\Models\User::findOrFail($userId);

        $oldStatus = $request->status;

        $request->update([
            'assigned_to' => $userId,
            'assigned_at' => now(),
            'status' => 'Assigned',
        ]);

        $this->log(
            $request,
            'Assigned',
            "Request assigned to {$user->name}.",
            $oldStatus,
            'Assigned'
        );
    }

    /**
     * Start work on an assigned request.
     */
    public function start(
        DocumentRequest $request
    ): void {
        $this->ensureStatus($request, 'Assigned');

        $oldStatus = $request->status;

        $request->update([
            'status' => 'Ongoing',
        ]);

        $this->log(
            $request,
            'Started',
            'Request work started.',
            $oldStatus,
            'Ongoing'
        );
    }

    /**
     * Complete an ongoing request.
     */
    public function complete(
        DocumentRequest $request
    ): void {
        $this->ensureStatus($request, 'Ongoing');

        $oldStatus = $request->status;

        $request->update([
            'status' => 'Completed',
            'completed_at' => now(),
        ]);

        $this->log(
            $request,
            'Completed',
            'Request marked as completed.',
            $oldStatus,
            'Completed'
        );
    }

    /**
     * Archive a completed request.
     */
    public function archive(
        DocumentRequest $request
    ): void {
        $this->ensureStatus($request, 'Completed');

        $oldStatus = $request->status;

        $request->update([
            'status' => 'Archived',
            'archived_at' => now(),
        ]);

        $this->log(
            $request,
            'Archived',
            'Request archived.',
            $oldStatus,
            'Archived'
        );
    }

    /**
     * Restore an archived request back to Incoming.
     */
    public function restore(
        DocumentRequest $request
    ): void {
        $this->ensureStatus($request, 'Archived');

        $oldStatus = $request->status;

        $request->update([
            'status' => 'Incoming',
            'assigned_to' => null,
            'assigned_at' => null,
            'completed_at' => null,
            'archived_at' => null,
        ]);

        $this->log(
            $request,
            'Restored',
            'Request restored to Incoming.',
            $oldStatus,
            'Incoming'
        );
    }

    /**
     * Record a workflow activity.
     */
    protected function log(
        DocumentRequest $request,
        string $action,
        string $description,
        ?string $oldValue = null,
        ?string $newValue = null
    ): void {
        ActivityLog::create([
            'request_id' => $request->id,
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'ip_address' => request()->ip(),
        ]);
    }

    /**
     * Ensure the request is currently in the expected status.
     */
    protected function ensureStatus(
        DocumentRequest $request,
        string $expectedStatus
    ): void {
        if ($request->status !== $expectedStatus) {
            throw ValidationException::withMessages([
                'status' => "This action is not allowed while the request is {$request->status}.",
            ]);
        }
    }
}