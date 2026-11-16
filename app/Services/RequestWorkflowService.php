<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\DocumentRequest;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class RequestWorkflowService
{
    /**
 * Assign an incoming request to an active Staff member.
 */
public function assign(
    DocumentRequest $request,
    int $userId
): void {
    $this->ensureAdmin();
    $this->ensureStatus($request, 'Incoming');

    if ($request->approval_status !== 'Approved') {
        throw ValidationException::withMessages([
            'approval_status' => 'Only approved requests can be assigned.',
        ]);
    }

    $user = User::query()
        ->whereKey($userId)
        ->where('role', 'Staff')
        ->where('is_active', true)
        ->first();

    if (! $user) {
        throw ValidationException::withMessages([
            'assigned_to' => 'The selected user must be an active Staff member.',
        ]);
    }

    $oldStatus = $request->status;

    $request->update([
        'assigned_to' => $user->id,
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
 * Assign an approved request to multiple directory personnel.
 */
public function assignPersonnel(
    DocumentRequest $request,
    array $personnelIds
): void {
    $this->ensureAdmin();
    $this->ensureStatus($request, 'Incoming');

    if ($request->approval_status !== 'Approved') {
        throw ValidationException::withMessages([
            'approval_status' => 'Only approved requests can be assigned.',
        ]);
    }

    $personnel = \App\Models\Personnel::query()
        ->whereIn('id', $personnelIds)
        ->where('is_active', true)
        ->get();

    if (
        $personnel->count() !== count(array_unique($personnelIds))
    ) {
        throw ValidationException::withMessages([
            'personnel_ids' => 'All selected personnel must be active.',
        ]);
    }

    $oldStatus = $request->status;

    // Save the directory assignments without using user-account IDs.
    $request->assignedPersonnel()->sync($personnel->modelKeys());

    $request->update([
        'assigned_to' => null,
        'assigned_at' => now(),
        'status' => 'Assigned',
    ]);

    $names = $personnel->pluck('name')->implode(', ');

    $this->log(
        $request,
        'Assigned',
        "Request assigned to: {$names}.",
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
    $this->ensureApproved($request);
    $this->ensureStatus($request, 'Assigned');
    $this->ensureCanWorkOnRequest($request);

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
 * Update the progress of an individual service.
 */
public function updateServiceProgress(
    DocumentRequest $request,
    int $serviceId,
    string $newStatus
): void {
    $this->ensureApproved($request);
    $this->ensureStatus($request, 'Ongoing');
    $this->ensureCanWorkOnRequest($request);

    if (! in_array($newStatus, ['Pending', 'Ongoing', 'Done'], true)) {
        throw ValidationException::withMessages([
            'status' => 'Invalid service progress status.',
        ]);
    }

    \Illuminate\Support\Facades\DB::transaction(function () use (
        $request,
        $serviceId,
        $newStatus
    ) {
        $request = DocumentRequest::whereKey($request->id)
            ->lockForUpdate()
            ->firstOrFail();

        $this->ensureApproved($request);
        $this->ensureStatus($request, 'Ongoing');
        $this->ensureCanWorkOnRequest($request);

        $service = $request->services()
            ->where('services.id', $serviceId)
            ->first();

        if (! $service) {
            throw ValidationException::withMessages([
                'service' => 'This service does not belong to the selected request.',
            ]);
        }

        $oldStatus = $service->pivot->status;

        if ($oldStatus === $newStatus) {
            throw ValidationException::withMessages([
                'status' => 'The service already has this progress status.',
            ]);
        }

        $request->services()->updateExistingPivot($serviceId, [
            'status' => $newStatus,
            'completed_at' => $newStatus === 'Done' ? now() : null,
        ]);

        \App\Models\ActivityLog::create([
            'request_id' => $request->id,
            'user_id' => auth()->id(),
            'action' => 'Service Progress Updated',
            'description' => "Service '{$service->name}' progress changed from {$oldStatus} to {$newStatus}.",
            'old_value' => $oldStatus,
            'new_value' => $newStatus,
            'ip_address' => request()->ip(),
        ]);

        $hasUnfinishedServices = $request->services()
            ->wherePivot('status', '!=', 'Done')
            ->exists();

        $hasServices = $request->services()->exists();

        if ($hasServices && ! $hasUnfinishedServices) {
            $this->complete($request);
        }
    });
}

    /**
 * Complete an ongoing request.
 */
public function complete(
    DocumentRequest $request
): void {
    $this->ensureApproved($request);
    $this->ensureStatus($request, 'Ongoing');
    $this->ensureCanWorkOnRequest($request);

        $hasServices = $request->services()->exists();

    $hasUnfinishedServices = $request->services()
        ->wherePivot('status', '!=', 'Done')
        ->exists();

    if ($hasServices && $hasUnfinishedServices) {
        throw ValidationException::withMessages([
            'services' => 'All requested services must be marked Done before completing this request.',
        ]);
    }

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
 * Ensure the current user is an administrator.
 */
protected function ensureAdmin(): void
{
    $user = auth()->user();

    if (! $user || ! $user->isAdmin()) {
        abort(403, 'Only administrators can perform this action.');
    }
}

/**
 * Ensure the request has been approved.
 */
protected function ensureApproved(
    DocumentRequest $request
): void {
    if ($request->approval_status !== 'Approved') {
        throw ValidationException::withMessages([
            'approval_status' => 'This request must be approved first.',
        ]);
    }
}


/**
 * Ensure Staff members work only on requests assigned to their
 * linked Personnel record or directly to their User account.
 * Administrators may work on any request.
 */
protected function ensureCanWorkOnRequest(
    DocumentRequest $request
): void {
    $user = auth()->user();

    if (! $user) {
        abort(401);
    }

    if ($user->isAdmin()) {
        return;
    }

    if (! $user->isStaff() || ! $user->is_active) {
        abort(403, 'You are not authorized to work on this request.');
    }

    // Preserve compatibility with requests assigned directly
    // to a Staff User account.
    $directAssignment = (int) $request->assigned_to === (int) $user->id;

    // Check whether this Staff account is linked to an active
    // Personnel record assigned to this request.
    $personnelAssignment = $request->assignedPersonnel()
        ->where('personnels.user_id', $user->id)
        ->where('personnels.is_active', true)
        ->exists();

    if (! $directAssignment && ! $personnelAssignment) {
        abort(403, 'You may only work on requests assigned to your account.');
    }
}

    /**
     * Archive a completed request.
     */
    public function archive(
        DocumentRequest $request
    ): void {
         $this->ensureAdmin();
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
        $this->ensureAdmin();
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