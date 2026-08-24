<?php

namespace App\Services;

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

        $request->update([
            'assigned_to' => $userId,
            'assigned_at' => now(),
            'status' => 'Assigned',
        ]);
    }

    /**
     * Start work on an assigned request.
     */
    public function start(
        DocumentRequest $request
    ): void {
        $this->ensureStatus($request, 'Assigned');

        $request->update([
            'status' => 'Ongoing',
        ]);
    }

    /**
     * Complete an ongoing request.
     */
    public function complete(
        DocumentRequest $request
    ): void {
        $this->ensureStatus($request, 'Ongoing');

        $request->update([
            'status' => 'Completed',
            'completed_at' => now(),
        ]);
    }

    /**
     * Archive a completed request.
     */
    public function archive(
        DocumentRequest $request
    ): void {
        $this->ensureStatus($request, 'Completed');

        $request->update([
            'status' => 'Archived',
            'archived_at' => now(),
        ]);
    }

    /**
     * Restore an archived request back to Incoming.
     */
    public function restore(
    DocumentRequest $request
): void {
    $this->ensureStatus($request, 'Archived');

    $request->update([
        'status' => 'Incoming',
        'assigned_to' => null,
        'assigned_at' => null,
        'completed_at' => null,
        'archived_at' => null,
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