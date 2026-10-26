@extends('layouts.master')

@section('page-title', 'Request Details')

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">

        <i class="bi bi-check-circle-fill me-2"></i>

        {{ session('success') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Close">
        </button>

    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">

        <i class="bi bi-exclamation-triangle-fill me-2"></i>

        {{ session('error') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Close">
        </button>

    </div>
@endif

<div class="container-fluid">

    <div class="row">

        {{-- ========================================= --}}
        {{-- Main Content --}}
        {{-- ========================================= --}}

        <div class="col-lg-8">

            {{-- Header --}}
            <div class="card shadow-sm mb-4">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <h3 class="fw-bold mb-1">

                                {{ $documentRequest->control_no }}

                            </h3>

                            <h5 class="text-muted">

                                {{ $documentRequest->services->first()?->name }}

                            </h5>

                        </div>

                        <div class="text-end">

                            @php
                                $statusColor = match($documentRequest->status){
                                    'Incoming' => 'warning',
                                    'Ongoing' => 'primary',
                                    'Completed' => 'success',
                                    'Archived' => 'secondary',
                                    default => 'dark',
                                };

                                $priorityColor = match($documentRequest->priority){
                                    'Urgent' => 'danger',
                                    'High' => 'warning',
                                    'Normal' => 'primary',
                                    'Low' => 'secondary',
                                    default => 'secondary',
                                };
                            @endphp

                            <span class="badge bg-{{ $statusColor }} fs-6">

                                {{ $documentRequest->status }}

                            </span>

                            <br><br>

                            <span class="badge bg-{{ $priorityColor }}">

                                {{ $documentRequest->priority }}

                            </span>

                        </div>

                    </div>

                </div>

            </div>

            {{-- ========================================= --}}
            {{-- Request Information --}}
            {{-- ========================================= --}}

            <div class="card shadow-sm mb-4">

                <div class="card-header">

                    <strong>

                        <i class="bi bi-file-earmark-text"></i>

                        Request Information

                    </strong>

                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="text-muted">
                                Organization
                            </label>

                            <div class="fw-semibold">

                                {{ $documentRequest->organization?->name ?? '-' }}

                            </div>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="text-muted">
                                Requestor
                            </label>

                            <div class="fw-semibold">

                                {{ $documentRequest->contact?->full_name ?? '-' }}

                            </div>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="text-muted">
                                Subject
                            </label>

                            <div class="fw-semibold">

                                {{ $documentRequest->subject }}

                            </div>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="text-muted">
                                Date Received
                            </label>

                            <div class="fw-semibold">

                                {{ $documentRequest->date_received?->format('F d, Y') }}

                            </div>

                        </div>

                        <div class="col-md-6 mb-3">

    <label class="text-muted">
        Due Date
    </label>

    <div class="fw-semibold">

        {{ $documentRequest->due_date?->format('F d, Y') ?? '-' }}

    </div>

</div>

                        <div class="col-12">

                            <label class="text-muted">

                                Description

                            </label>

                            <div class="fw-semibold">

                                {{ $documentRequest->description ?: '-' }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- ========================================= --}}
            {{-- Service Information --}}
            {{-- ========================================= --}}

            <div class="card shadow-sm">

                <div class="card-header">

                    <strong>

                        <i class="bi bi-list-check"></i>

                        Service Information

                    </strong>

                </div>

                <div class="card-body">

          @forelse($documentRequest->answers as $answer)

    @php

        $type = $answer->field->fieldType->key;

        $value = $answer->value;

    @endphp

    <div class="row mb-3">

        <div class="col-md-4">

            <strong>

                {{ $answer->field->label }}

            </strong>

        </div>

        <div class="col-md-8">

            @switch($type)

                {{-- ===================================== --}}
                {{-- Date --}}
                {{-- ===================================== --}}

                @case('date')

                    {{ \Carbon\Carbon::parse($value)->format('F d, Y') }}

                    @break


                {{-- ===================================== --}}
{{-- Time --}}
{{-- ===================================== --}}

@case('time')

    {{ \Carbon\Carbon::parse($value)->format('h:i A') }}

    @break


{{-- ===================================== --}}
{{-- Schedule --}}
{{-- ===================================== --}}

@case('schedule')

    @php
        $schedules = json_decode($value, true);
    @endphp

    @if(is_array($schedules) && count($schedules))

        <div class="d-flex flex-column gap-2">

            @foreach($schedules as $schedule)

                <div class="border rounded p-2 bg-light">

                    <div class="fw-semibold">
                        <i class="bi bi-calendar-event me-1"></i>

                        @if(!empty($schedule['date']))
                            {{ \Carbon\Carbon::parse($schedule['date'])->format('F d, Y') }}
                        @else
                            -
                        @endif
                    </div>

                    <div class="small text-muted mt-1">

                        @if(!empty($schedule['start_time']) && !empty($schedule['end_time']))

                            <i class="bi bi-clock me-1"></i>

                            {{ \Carbon\Carbon::parse($schedule['start_time'])->format('h:i A') }}
                            –
                            {{ \Carbon\Carbon::parse($schedule['end_time'])->format('h:i A') }}

                        @else

                            -

                        @endif

                    </div>

                </div>

            @endforeach

        </div>

    @else

        -

    @endif

    @break


{{-- ===================================== --}}
{{-- Checkbox --}}
{{-- ===================================== --}}

                @case('checkbox')

                    @php

                        $items = json_decode($value, true);

                    @endphp

                    @if(is_array($items))

                        @foreach($items as $item)

                            <span class="badge bg-primary me-1">

                                {{ $item }}

                            </span>

                        @endforeach

                    @else

                        -

                    @endif

                    @break


                {{-- ===================================== --}}
                {{-- Select --}}
                {{-- ===================================== --}}

                @case('select')

                    @php

                        $option = $answer->field
                            ->options
                            ->firstWhere('value', $value);

                    @endphp

                    {{ $option?->label ?? $value }}

                    @break


                {{-- ===================================== --}}
                {{-- File --}}
                {{-- ===================================== --}}

                @case('file')

                    @if($value)

                        <a
                            href="{{ asset('storage/'.$value) }}"
                            target="_blank"
                            class="btn btn-sm btn-outline-primary">

                            <i class="bi bi-download"></i>

                            View Attachment

                        </a>

                    @else

                        -

                    @endif

                    @break


                {{-- ===================================== --}}
                {{-- Default --}}
                {{-- ===================================== --}}

                @default

                    {{ $value }}

            @endswitch

        </div>

    </div>

@empty

    <div class="text-muted">

        No service information.

    </div>

@endforelse

                </div>

            </div>

        </div>

        {{-- ========================================= --}}
        {{-- Sidebar --}}
        {{-- ========================================= --}}

        <div class="col-lg-4">

    {{-- ========================================= --}}
    {{-- Workflow --}}
    {{-- ========================================= --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header">

            <strong>

                <i class="bi bi-diagram-3 me-2"></i>

                Workflow

            </strong>

        </div>
{{-- Workflow Progress --}}
<div class="card-body border-bottom">

    @php
        $workflowSteps = [
            'Incoming',
            'Assigned',
            'Ongoing',
            'Completed',
            'Archived',
        ];

        $workflowOrder = [
            'Incoming' => 0,
            'Assigned' => 1,
            'Ongoing' => 2,
            'Completed' => 3,
            'Archived' => 4,
        ];

        $currentStep = $workflowOrder[$documentRequest->status] ?? 0;
    @endphp

    <div class="workflow-progress">

        @foreach($workflowSteps as $index => $step)

            @php
                $stepIndex = $workflowOrder[$step];

                if ($stepIndex < $currentStep) {
                    $stepState = 'completed';
                } elseif ($stepIndex === $currentStep) {
                    $stepState = 'current';
                } else {
                    $stepState = 'upcoming';
                }
            @endphp

            <div class="workflow-step {{ $stepState }}">

                <div class="workflow-circle">

                    @if($stepState === 'completed')
                        <i class="bi bi-check"></i>
                    @else
                        {{ $index + 1 }}
                    @endif

                </div>

                <div class="workflow-label">
                    {{ $step }}
                </div>

            </div>

            @if(!$loop->last)
                <div class="workflow-line
                    {{ $stepIndex < $currentStep ? 'completed' : '' }}">
                </div>
            @endif

        @endforeach

    </div>

</div>

        <div class="card-body">

            <div class="mb-3">

                <label class="text-muted">

                    Current Status

                </label>

                <div>

                    @php

                        $statusColor = match($documentRequest->status){

                            'Incoming' => 'warning',

                            'Assigned' => 'info',

                            'Ongoing' => 'primary',

                            'Completed' => 'success',

                            'Archived' => 'secondary',

                            default => 'dark',

                        };

                    @endphp

                    <span class="badge bg-{{ $statusColor }} fs-6">

                        {{ $documentRequest->status }}

                    </span>

                </div>


            </div>

            {{-- Approval Status --}}
<div class="mt-3 mb-3">

    <label class="text-muted">
        Approval Status
    </label>

    @php
        $approvalColor = match($documentRequest->approval_status) {
            'Pending' => 'warning',
            'Approved' => 'success',
            'Declined' => 'danger',
            'Cancelled' => 'secondary',
            default => 'dark',
        };

        $approvalIcon = match($documentRequest->approval_status) {
            'Pending' => 'hourglass-split',
            'Approved' => 'check-circle-fill',
            'Declined' => 'x-circle-fill',
            'Cancelled' => 'slash-circle-fill',
            default => 'question-circle',
        };
    @endphp

    <div>
        <span class="badge bg-{{ $approvalColor }} fs-6">
            <i class="bi bi-{{ $approvalIcon }} me-1"></i>
            {{ $documentRequest->approval_status }}
        </span>
    </div>

</div>

{{-- Approval Decision Details --}}
@if(
    $documentRequest->approval_status !== 'Pending' &&
    $documentRequest->approval_reviewed_by
)

    <div class="border rounded p-3 mb-3 approval-decision-box approval-{{ strtolower($documentRequest->approval_status) }}">

        <div class="small fw-semibold mb-2">
    @if($documentRequest->approval_status === 'Approved')
        <i class="bi bi-check-circle-fill text-success me-1"></i>
    @elseif($documentRequest->approval_status === 'Declined')
        <i class="bi bi-x-circle-fill text-danger me-1"></i>
    @elseif($documentRequest->approval_status === 'Cancelled')
        <i class="bi bi-slash-circle-fill text-secondary me-1"></i>
    @endif

    Approval Decision
</div>

        <div class="mb-2">

            <label class="small text-muted d-block">
                Reviewed By
            </label>

            <div class="fw-semibold">
                {{ $documentRequest->approvalReviewer?->name ?? '-' }}
            </div>

        </div>

        <div class="mb-2">

            <label class="small text-muted d-block">
                Reviewed On
            </label>

            <div class="fw-semibold">
                {{ optional($documentRequest->approval_reviewed_at)->format('M d, Y h:i A') ?? '-' }}
            </div>

        </div>

        @if($documentRequest->approval_review_note)

            <div>

                <label class="small text-muted d-block">
                    {{ $documentRequest->approval_status === 'Approved' ? 'Review Note' : 'Reason' }}
                </label>

                <div class="small">
                    {{ $documentRequest->approval_review_note }}
                </div>

            </div>

        @endif

    </div>

@endif

            @if($documentRequest->assignedTo)

                <div class="mb-3">

                    <label class="text-muted">

                        Assigned To

                    </label>

                    <div class="fw-semibold">

                        {{ $documentRequest->assignedTo->name }}

                    </div>

                    <small class="text-muted">

                        {{ optional($documentRequest->assigned_at)->format('M d, Y h:i A') }}

                    </small>

                </div>

            @endif

            @switch($documentRequest->status)

    {{-- ===================================================== --}}
    {{-- Incoming --}}
    {{-- ===================================================== --}}
    @case('Incoming')

        {{-- =============================================== --}}
        {{-- Pending Approval --}}
        {{-- =============================================== --}}
        @if($documentRequest->approval_status === 'Pending')

            <div class="alert alert-warning">
                <div class="d-flex align-items-start">
                    <i class="bi bi-hourglass-split fs-4 me-2"></i>

                    <div>
                        <strong>Awaiting Administrator Approval</strong>

                        <div class="small mt-1">
                            This request must be approved before it can be assigned
                            and processed.
                        </div>
                    </div>
                </div>
            </div>

            {{-- Admin Approval Actions --}}
            @if(auth()->user()->role === 'Admin')

                <div class="d-grid gap-2">

                    {{-- Approve --}}
                    <button
                        type="button"
                        class="btn btn-success"
                        data-bs-toggle="modal"
                        data-bs-target="#approveRequestModal">

                        <i class="bi bi-check-circle me-1"></i>
                        Approve Request

                    </button>

                    {{-- Decline --}}
                    <button
                        type="button"
                        class="btn btn-outline-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#declineRequestModal">

                        <i class="bi bi-x-circle me-1"></i>
                        Decline Request

                    </button>

                    {{-- Cancel --}}
                    <button
                        type="button"
                        class="btn btn-outline-secondary"
                        data-bs-toggle="modal"
                        data-bs-target="#cancelRequestModal">

                        <i class="bi bi-slash-circle me-1"></i>
                        Cancel Request

                    </button>

                </div>

            @endif

        {{-- =============================================== --}}
        {{-- Approved --}}
        {{-- =============================================== --}}
        @elseif($documentRequest->approval_status === 'Approved')

            <div class="alert alert-success">
                <div class="d-flex align-items-start">

                    <i class="bi bi-check-circle-fill fs-4 me-2"></i>

                    <div>
                        <strong>Request Approved</strong>

                        <div class="small mt-1">
                            This request has been approved and may now be assigned
                            for processing.
                        </div>
                    </div>

                </div>
            </div>

            {{-- Assign Request --}}
            <form
                method="POST"
                action="{{ route('requests.assign', $documentRequest) }}">

                @csrf

                <div class="mb-3">

                    <label class="form-label">
                        Assign To
                    </label>

                    <select
                        name="assigned_to"
                        class="form-select"
                        required>

                        <option value="">
                            Select User
                        </option>

                        @foreach($users as $user)

                            <option value="{{ $user->id }}">

                                {{ $user->name }}

                            </option>

                        @endforeach

                    </select>

                </div>

                <button
                    class="btn btn-primary w-100">

                    <i class="bi bi-person-check-fill me-1"></i>

                    Assign Request

                </button>

            </form>

        {{-- =============================================== --}}
        {{-- Declined --}}
        {{-- =============================================== --}}
        @elseif($documentRequest->approval_status === 'Declined')

            <div class="alert alert-danger">

                <div class="d-flex align-items-start">

                    <i class="bi bi-x-circle-fill fs-4 me-2"></i>

                    <div class="flex-grow-1">

                        <strong>Request Declined</strong>

                        <div class="small mt-1">
                            This request was declined by an administrator.
                        </div>

                        @if($documentRequest->approval_review_note)

                            <hr>

                            <div>
                                <strong>Review Note:</strong>
                            </div>

                            <div class="small">
                                {{ $documentRequest->approval_review_note }}
                            </div>

                        @endif

                    </div>

                </div>

            </div>

            {{-- Admin can restore for approval --}}
            @if(auth()->user()->role === 'Admin')

                <button
                    type="button"
                    class="btn btn-warning w-100"
                    data-bs-toggle="modal"
                    data-bs-target="#restoreForApprovalModal">

                    <i class="bi bi-arrow-counterclockwise me-1"></i>

                    Restore for Approval

                </button>

            @endif

        {{-- =============================================== --}}
        {{-- Cancelled --}}
        {{-- =============================================== --}}
        @elseif($documentRequest->approval_status === 'Cancelled')

            <div class="alert alert-secondary">

                <div class="d-flex align-items-start">

                    <i class="bi bi-slash-circle-fill fs-4 me-2"></i>

                    <div class="flex-grow-1">

                        <strong>Request Cancelled</strong>

                        <div class="small mt-1">
                            This request was cancelled by an administrator.
                        </div>

                        @if($documentRequest->approval_review_note)

                            <hr>

                            <div>
                                <strong>Review Note:</strong>
                            </div>

                            <div class="small">
                                {{ $documentRequest->approval_review_note }}
                            </div>

                        @endif

                    </div>

                </div>

            </div>

            {{-- Admin can restore for approval --}}
            @if(auth()->user()->role === 'Admin')

                <button
                    type="button"
                    class="btn btn-warning w-100"
                    data-bs-toggle="modal"
                    data-bs-target="#restoreForApprovalModal">

                    <i class="bi bi-arrow-counterclockwise me-1"></i>

                    Restore for Approval

                </button>

            @endif

        @endif

        @break


    {{-- ===================================================== --}}
    {{-- Assigned --}}
    {{-- ===================================================== --}}
    @case('Assigned')

        <form
            method="POST"
            action="{{ route('requests.start', $documentRequest) }}">

            @csrf

            <button
                class="btn btn-success w-100">

                <i class="bi bi-play-circle me-1"></i>

                Start Work

            </button>

        </form>

        @break


    {{-- ===================================================== --}}
    {{-- Ongoing --}}
    {{-- ===================================================== --}}
    @case('Ongoing')

        <form
            method="POST"
            action="{{ route('requests.complete', $documentRequest) }}">

            @csrf

            <button
                class="btn btn-primary w-100">

                <i class="bi bi-check-circle me-1"></i>

                Mark Completed

            </button>

        </form>

        @break


    {{-- ===================================================== --}}
    {{-- Completed --}}
    {{-- ===================================================== --}}
    @case('Completed')

        @if(auth()->user()->role === 'Admin')

            {{-- Admin can directly archive --}}
            <form
                method="POST"
                action="{{ route('requests.archive', $documentRequest) }}">

                @csrf

                <button
                    type="submit"
                    class="btn btn-dark w-100"
                    onclick="return confirm('Are you sure you want to archive this request?');">

                    <i class="bi bi-archive me-1"></i>

                    Archive Request

                </button>

            </form>

        @else

            {{-- Staff must request archive --}}
            <button
                type="button"
                class="btn btn-outline-dark w-100"
                data-bs-toggle="modal"
                data-bs-target="#archiveRequestModal">

                <i class="bi bi-archive me-1"></i>

                Request Archive

            </button>

        @endif

        @break


    {{-- ===================================================== --}}
    {{-- Archived --}}
    {{-- ===================================================== --}}
    @case('Archived')

        <div class="alert alert-secondary mb-3">

            <i class="bi bi-archive me-1"></i>

            This request is currently archived.

        </div>

        @if(auth()->user()->role === 'Admin')

            {{-- Admin can directly restore --}}
            <form
                method="POST"
                action="{{ route('requests.restore', $documentRequest) }}">

                @csrf

                <button
                    type="submit"
                    class="btn btn-success w-100"
                    onclick="return confirm('Restore this request and move it back to Completed?');">

                    <i class="bi bi-arrow-counterclockwise me-1"></i>

                    Restore Request

                </button>

            </form>

        @else

            {{-- Staff must request restore --}}
            <button
                type="button"
                class="btn btn-outline-success w-100"
                data-bs-toggle="modal"
                data-bs-target="#restoreRequestModal">

                <i class="bi bi-arrow-counterclockwise me-1"></i>

                Request Restore

            </button>

        @endif

        @break

@endswitch

        </div>
        {{-- ========================================================= --}}
{{-- Archive Request Modal --}}
{{-- ========================================================= --}}

@if(auth()->user()->role !== 'Admin' && $documentRequest->status === 'Completed')

    <div
        class="modal fade"
        id="archiveRequestModal"
        tabindex="-1"
        aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        <i class="bi bi-archive me-2"></i>
                        Request Archive
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>

                </div>

                <form
                    method="POST"
                    action="{{ route('request-action-requests.store', $documentRequest) }}">

                    @csrf

                    <input
                        type="hidden"
                        name="action"
                        value="Archive">

                    <div class="modal-body">

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Document Request
                            </label>

                            <div class="form-control bg-light">
                                {{ $documentRequest->control_no }}
                            </div>

                        </div>

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Reason for Archive
                            </label>

                            <textarea
                                name="reason"
                                class="form-control"
                                rows="4"
                                placeholder="Please explain why this request should be archived."
                                required></textarea>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                            Cancel

                        </button>

                        <button
                            type="submit"
                            class="btn btn-dark">

                            <i class="bi bi-send me-1"></i>
                            Submit Archive Request

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endif

    </div>
    {{-- ========================================================= --}}
{{-- Restore Request Modal --}}
{{-- ========================================================= --}}

@if(auth()->user()->role !== 'Admin' && $documentRequest->status === 'Archived')

    <div
        class="modal fade"
        id="restoreRequestModal"
        tabindex="-1"
        aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        <i class="bi bi-arrow-counterclockwise me-2"></i>
                        Request Restore
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>

                </div>

                <form
                    method="POST"
                    action="{{ route('request-action-requests.store', $documentRequest) }}">

                    @csrf

                    <input
                        type="hidden"
                        name="action"
                        value="Restore">

                    <div class="modal-body">

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Document Request
                            </label>

                            <div class="form-control bg-light">
                                {{ $documentRequest->control_no }}
                            </div>

                        </div>

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Reason for Restore
                            </label>

                            <textarea
                                name="reason"
                                class="form-control"
                                rows="4"
                                placeholder="Please explain why this archived request should be restored."
                                required></textarea>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                            Cancel

                        </button>

                        <button
                            type="submit"
                            class="btn btn-success">

                            <i class="bi bi-send me-1"></i>
                            Submit Restore Request

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endif

{{-- Approve Request Modal --}}
<div
    class="modal fade"
    id="approveRequestModal"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    <i class="bi bi-check-circle text-success me-1"></i>
                    Approve Request
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <form
                method="POST"
                action="{{ route('requests.approve', $documentRequest) }}">

                @csrf

                <div class="modal-body">

                    <p>
                        Are you sure you want to approve this request?
                    </p>

                    <div class="mb-3">

                        <label class="form-label">
                            Review Note
                            <span class="text-muted">(Optional)</span>
                        </label>

                        <textarea
                            name="approval_review_note"
                            class="form-control"
                            rows="4"
                            placeholder="Enter an optional approval note..."></textarea>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button
                        type="submit"
                        class="btn btn-success">

                        <i class="bi bi-check-circle me-1"></i>
                        Approve Request

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

{{-- Decline Request Modal --}}
<div
    class="modal fade"
    id="declineRequestModal"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">

                    <i class="bi bi-x-circle text-danger me-1"></i>

                    Decline Request

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <form
                method="POST"
                action="{{ route('requests.decline', $documentRequest) }}">

                @csrf

                <div class="modal-body">

                    <div class="alert alert-warning">

                        Please provide a reason for declining this request.

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Reason

                            <span class="text-danger">*</span>

                        </label>

                        <textarea
                            name="approval_review_note"
                            class="form-control"
                            rows="4"
                            required
                            placeholder="Enter reason for declining..."></textarea>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button
                        type="submit"
                        class="btn btn-danger">

                        <i class="bi bi-x-circle me-1"></i>

                        Decline Request

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

{{-- Cancel Request Modal --}}
<div
    class="modal fade"
    id="cancelRequestModal"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">

                    <i class="bi bi-slash-circle text-secondary me-1"></i>

                    Cancel Request

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <form
                method="POST"
                action="{{ route('requests.cancel', $documentRequest) }}">

                @csrf

                <div class="modal-body">

                    <div class="alert alert-warning">

                        Please provide a reason for cancelling this request.

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Reason

                            <span class="text-danger">*</span>

                        </label>

                        <textarea
                            name="approval_review_note"
                            class="form-control"
                            rows="4"
                            required
                            placeholder="Enter reason for cancelling..."></textarea>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Close

                    </button>

                    <button
                        type="submit"
                        class="btn btn-secondary">

                        <i class="bi bi-slash-circle me-1"></i>

                        Cancel Request

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

{{-- Restore for Approval Modal --}}
<div
    class="modal fade"
    id="restoreForApprovalModal"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">

                    <i class="bi bi-arrow-counterclockwise text-warning me-1"></i>

                    Restore for Approval

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <form
                method="POST"
                action="{{ route('requests.restore-for-approval', $documentRequest) }}">

                @csrf

                <div class="modal-body">

                    <div class="alert alert-warning">

                        <strong>Important:</strong>

                        Restoring this request will return it to
                        <strong>Pending Approval</strong>.

                        It will <strong>not</strong> automatically approve it.

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Reason / Review Note

                            <span class="text-danger">*</span>

                        </label>

                        <textarea
                            name="approval_review_note"
                            class="form-control"
                            rows="4"
                            required
                            placeholder="Enter reason for restoring this request for approval..."></textarea>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Close

                    </button>

                    <button
                        type="submit"
                        class="btn btn-warning">

                        <i class="bi bi-arrow-counterclockwise me-1"></i>

                        Restore for Approval

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


    {{-- ========================================= --}}
    {{-- Actions --}}
    {{-- ========================================= --}}

    <div class="card shadow-sm">

        <div class="card-header">

            <strong>

                Actions

            </strong>

        </div>

        <div class="card-body d-grid gap-2">

            <a
                href="{{ route('requests.index') }}"
                class="btn btn-secondary">

                <i class="bi bi-arrow-left"></i>

                Back

            </a>

            @if(auth()->user()->role === 'Admin')
    <a
        href="{{ route('requests.edit', ['documentRequest' => $documentRequest]) }}"
        class="btn btn-primary">

        <i class="bi bi-pencil"></i>
        Edit Request

    </a>
@endif

            <a
    href="{{ route('requests.print', $documentRequest) }}"
    class="btn btn-outline-dark"
    target="_blank">

    <i class="bi bi-printer"></i>

    Print

</a>
        </div>

    </div>

</div>

{{-- ========================================= --}}
{{-- Attachments --}}
{{-- ========================================= --}}

<div class="card shadow-sm mt-4 mb-4">

    <div class="card-header d-flex justify-content-between align-items-center">

        <strong>
            <i class="bi bi-paperclip me-1"></i>
            Attachments
        </strong>

        <span class="badge bg-secondary">
            {{ $documentRequest->attachments->count() }}
        </span>

    </div>

    <div class="card-body">

        {{-- Upload Form --}}
        <form
            method="POST"
            action="{{ route('requests.attachments.store', $documentRequest) }}"
            enctype="multipart/form-data">

            @csrf

            <div class="row g-3">

                {{-- File --}}
                <div class="col-md-7">

                    <label class="form-label fw-semibold">
                        File
                    </label>

                    <input
                        type="file"
                        name="attachment"
                        class="form-control"
                        required>

                    <small class="text-muted">
                        Maximum 20 MB.
                    </small>

                </div>

                {{-- Attachment Type --}}
                <div class="col-md-5">

                    <label class="form-label fw-semibold">
                        Attachment Type
                    </label>

                    <select
                        name="attachment_type"
                        class="form-select"
                        required>

                        <option value="">
                            Select Type
                        </option>

                        <option value="Request Letter">
                            Request Letter
                        </option>

                        <option value="Program">
                            Program
                        </option>

                        <option value="Layout">
                            Layout
                        </option>

                        <option value="Memorandum">
                            Memorandum
                        </option>

                        <option value="Photo">
                            Photo
                        </option>

                        <option value="Video">
                            Video
                        </option>

                        <option value="Document">
                            Document
                        </option>

                        <option value="Others">
                            Others
                        </option>

                    </select>

                </div>

                {{-- Upload Button --}}
                <div class="col-12">

                    <button
                        type="submit"
                        class="btn btn-primary">

                        <i class="bi bi-cloud-arrow-up me-1"></i>

                        Upload Attachment

                    </button>

                </div>

            </div>

        </form>

        <hr class="my-4">

        {{-- Existing Attachments --}}
        @forelse($documentRequest->attachments as $attachment)

    <div class="d-flex align-items-center justify-content-between border rounded p-3 mb-2">

        {{-- File Information --}}
        <div class="d-flex align-items-center">

            <div class="me-3">

                @php
                    $icon = match(strtolower($attachment->file_type)) {
                        'pdf' => 'bi-file-earmark-pdf text-danger',
                        'doc', 'docx' => 'bi-file-earmark-word text-primary',
                        'xls', 'xlsx' => 'bi-file-earmark-excel text-success',
                        'ppt', 'pptx' => 'bi-file-earmark-slides text-warning',
                        'jpg', 'jpeg', 'png', 'gif', 'webp' => 'bi-file-earmark-image text-info',
                        'mp4', 'mov' => 'bi-file-earmark-play text-primary',
                        default => 'bi-file-earmark text-secondary',
                    };
                @endphp

                <i class="bi {{ $icon }} fs-3"></i>

            </div>

            <div>

                <div class="fw-semibold">

                    {{ $attachment->file_name }}

                </div>

                <small class="text-muted">

                    {{ $attachment->attachment_type }}

                    ·

                    {{ number_format($attachment->file_size / 1024, 1) }} KB

                    ·

                    {{ $attachment->uploadedBy?->name ?? 'Unknown User' }}

                </small>

            </div>

        </div>

        {{-- Actions --}}
<div class="d-flex gap-2">

    <a
        href="{{ route('attachments.show', $attachment) }}"
        target="_blank"
        class="btn btn-sm btn-outline-primary"
        title="View">

        <i class="bi bi-eye"></i>
        View

    </a>

    <a
        href="{{ route('attachments.download', $attachment) }}"
        class="btn btn-sm btn-outline-secondary"
        title="Download">

        <i class="bi bi-download"></i>
        Download

    </a>

    @if(auth()->user()->role === 'Admin')

        {{-- Admin can directly delete --}}
        <form
            method="POST"
            action="{{ route('attachments.destroy', $attachment) }}"
            class="d-inline"
            onsubmit="return confirm('Are you sure you want to delete this attachment?');">

            @csrf
            @method('DELETE')

            <button
                type="submit"
                class="btn btn-sm btn-outline-danger"
                title="Delete">

                <i class="bi bi-trash"></i>
                Delete

            </button>

        </form>

    @else

        {{-- Staff can request deletion --}}
        <button
            type="button"
            class="btn btn-sm btn-outline-warning"
            data-bs-toggle="modal"
            data-bs-target="#deletionRequestModal{{ $attachment->id }}">

            <i class="bi bi-trash"></i>
            Request Deletion

        </button>

    @endif

</div>
@if(auth()->user()->role !== 'Admin')

    {{-- Deletion Request Modal --}}
    <div
        class="modal fade"
        id="deletionRequestModal{{ $attachment->id }}"
        tabindex="-1"
        aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Request File Deletion
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>

                </div>

                <form
                    method="POST"
                    action="{{ route('attachments.deletion-request.store', $attachment) }}">

                    @csrf

                    <div class="modal-body">

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                File
                            </label>

                            <div class="form-control bg-light">
                                {{ $attachment->file_name }}
                            </div>

                        </div>

                        <div class="mb-3">

                            <label
                                for="reason{{ $attachment->id }}"
                                class="form-label fw-semibold">

                                Reason for deletion
                            </label>

                            <textarea
                                name="reason"
                                id="reason{{ $attachment->id }}"
                                class="form-control"
                                rows="4"
                                placeholder="Please explain why this file should be deleted."
                                required></textarea>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                            Cancel

                        </button>

                        <button
                            type="submit"
                            class="btn btn-warning">

                            <i class="bi bi-send"></i>
                            Submit Request

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endif

    </div>

@empty

    <div class="text-center text-muted py-3">

        <i class="bi bi-paperclip fs-3 d-block mb-2"></i>

        No attachments uploaded yet.

    </div>

@endforelse

    </div>

</div>

{{-- ========================================= --}}
{{-- Activity History --}}
{{-- ========================================= --}}

<div class="card shadow-sm mt-4 mb-4">

    <div class="card-header">

        <strong>

            <i class="bi bi-clock-history me-1"></i>

            Activity History

        </strong>

    </div>

    <div class="card-body">



        <div class="activity-timeline">

    @forelse($documentRequest->activityLogs as $activity)

       @php
    $markerClass = match($activity->action) {
        'Request Approved' => 'activity-marker-success',
        'Request Declined' => 'activity-marker-danger',
        'Request Cancelled' => 'activity-marker-secondary',
        'Request Restored for Approval' => 'activity-marker-warning',

        'Assigned' => 'activity-marker-primary',
        'Started' => 'activity-marker-primary',
        'Completed' => 'activity-marker-success',
        'Archived' => 'activity-marker-secondary',
        'Restored' => 'activity-marker-success',

        'Attachment Uploaded' => 'activity-marker-primary',
        'Attachment Deleted' => 'activity-marker-danger',

        default => 'activity-marker-primary',
    };
@endphp

        <div class="activity-item">

            {{-- Timeline Marker --}}
            <div class="activity-marker {{ $markerClass }}">

                @switch($activity->action)

                    @case('Request Approved')
    <i class="bi bi-check-circle-fill"></i>
    @break

@case('Request Declined')
    <i class="bi bi-x-circle-fill"></i>
    @break

@case('Request Cancelled')
    <i class="bi bi-slash-circle-fill"></i>
    @break

@case('Request Restored for Approval')
    <i class="bi bi-arrow-counterclockwise"></i>
    @break

                    @case('Assigned')
                        <i class="bi bi-person-check"></i>
                        @break

                    @case('Started')
                        <i class="bi bi-play-circle"></i>
                        @break

                    @case('Completed')
                        <i class="bi bi-check-circle"></i>
                        @break

                    @case('Archived')
                        <i class="bi bi-archive"></i>
                        @break

                    @case('Restored')
                        <i class="bi bi-arrow-counterclockwise"></i>
                        @break
                    @case('Attachment Uploaded')

    <i class="bi bi-paperclip"></i>

    @break

@case('Attachment Deleted')

    <i class="bi bi-trash"></i>

    @break

                    @default
                        <i class="bi bi-activity"></i>

                @endswitch

            </div>

            {{-- Activity Details --}}
            <div class="activity-content">

                <div class="activity-header">

                    <div>
                        <strong class="activity-title">
                            {{ $activity->action }}
                        </strong>

                        @if($activity->user)
                            <span class="activity-user">
                                <i class="bi bi-person me-1"></i>
                                {{ $activity->user->name }}
                            </span>
                        @endif
                    </div>

                    <small class="activity-time">
                        {{ $activity->created_at->format('M d, Y h:i A') }}
                    </small>

                </div>

                <div class="activity-description">

                    {{ $activity->description }}

                </div>

                @if($activity->old_value || $activity->new_value)

                    <div class="activity-status">

                        <span class="text-muted">
                            Status:
                        </span>

                        <strong>
                            {{ $activity->old_value ?? '-' }}
                        </strong>

                        <i class="bi bi-arrow-right mx-1"></i>

                        <strong>
                            {{ $activity->new_value ?? '-' }}
                        </strong>

                    </div>

                @endif

            </div>

        </div>

    @empty

        <div class="text-center text-muted py-4">

            <i class="bi bi-clock-history fs-2 d-block mb-2"></i>

            No activity recorded yet.

        </div>

    @endforelse

</div>

    <style>

        .approval-decision-box {
    border-left-width: 4px !important;
}

.approval-decision-box.approval-approved {
    border-left-color: #198754 !important;
}

.approval-decision-box.approval-declined {
    border-left-color: #dc3545 !important;
}

.approval-decision-box.approval-cancelled {
    border-left-color: #6c757d !important;
}

    .activity-timeline {
        position: relative;
        padding-left: 10px;
    }

    .activity-item {
        position: relative;
        display: flex;
        gap: 15px;
        padding-bottom: 26px;
    }

    .activity-item:last-child {
        padding-bottom: 0;
    }

    /* Vertical connecting line */
    .activity-item:not(:last-child)::before {
        content: '';
        position: absolute;

        left: 18px;
        top: 36px;
        bottom: 0;

        width: 2px;

        background: #e9ecef;
    }

    /* Timeline marker */
    .activity-marker {
        position: relative;
        z-index: 2;

        flex: 0 0 36px;

        width: 36px;
        height: 36px;

        border-radius: 50%;

        display: flex;
        align-items: center;
        justify-content: center;

        color: #fff;

        font-size: 14px;

        box-shadow: 0 0 0 4px #fff;
    }

.workflow-progress {
    display: flex;
    align-items: flex-start;
    width: 100%;
}

.workflow-step {
    flex: 1 1 0;
    min-width: 0;
    text-align: center;
}

.workflow-circle {
    width: 36px;
    height: 36px;
    margin: 0 auto 8px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    border: 2px solid #dee2e6;
    background: #fff;
    color: #6c757d;
}

.workflow-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #6c757d;
    white-space: nowrap;
}

.workflow-line {
    flex: 1 1 0;
    height: 3px;
    background: #dee2e6;
    margin-top: 17px;
    min-width: 10px;
}

.workflow-step.completed .workflow-circle {
    background: #198754;
    border-color: #198754;
    color: #fff;
}

.workflow-step.completed .workflow-label {
    color: #198754;
}

.workflow-line.completed {
    background: #198754;
}

.workflow-step.current .workflow-circle {
    background: #0d6efd;
    border-color: #0d6efd;
    color: #fff;
    box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.15);
}

.workflow-step.current .workflow-label {
    color: #0d6efd;
}

.workflow-step.upcoming .workflow-circle {
    background: #f8f9fa;
}

@media (max-width: 576px) {

    .workflow-step {
        min-width: 65px;
    }

    .workflow-label {
        font-size: 0.7rem;
    }

    .workflow-line {
        min-width: 10px;
    }

}
    /* Marker colors */
    .activity-marker-primary {
        background: #0d6efd;
    }

    .activity-marker-success {
        background: #198754;
    }

    .activity-marker-secondary {
        background: #6c757d;
    }
    .activity-marker-danger {
    background: #dc3545;
}

.activity-marker-warning {
    background: #ffc107;
    color: #212529;
}

    /* Content */
    .activity-content {
        flex: 1;
        min-width: 0;

        padding-top: 1px;
    }

    /* Title + timestamp */
    .activity-header {
        display: flex;
        align-items: center;
        gap: 15px;

        margin-bottom: 3px;
    }

    .activity-title {
        color: #212529;
        font-size: 14px;
    }

    .activity-time {
        color: #6c757d;
        font-size: 12px;
        white-space: nowrap;
    }

    /* User */
    .activity-user {
        margin-left: 8px;

        color: #6c757d;

        font-size: 12px;
        font-weight: normal;
    }

    /* Description */
    .activity-description {
        color: #6c757d;
        font-size: 13px;

        margin-bottom: 3px;
    }

    /* Status transition */
    .activity-status {
        font-size: 12px;
        color: #495057;
    }

    .activity-status strong {
        color: #212529;
    }

    /* Mobile */
    @media (max-width: 576px) {

    .workflow-label {
        font-size: 0.65rem;
    }

    .workflow-circle {
        width: 32px;
        height: 32px;
        font-size: 0.85rem;
    }

    .workflow-line {
        margin-top: 15px;
        min-width: 5px;
    }

}

</style>

</div>

    </div>

</div>

    </div>
@endsection
