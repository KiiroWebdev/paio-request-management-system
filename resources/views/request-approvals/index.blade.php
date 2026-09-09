@extends('layouts.master')

@section('page-title', 'Request Approvals')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="mb-4">

    <div class="mb-4">

        <h3 class="fw-bold mb-1">
            <i class="bi bi-check2-square me-2"></i>
            Request Approvals
        </h3>

        <p class="text-muted mb-0">
            Review and decide on incoming requests.
        </p>

    </div>


    {{-- Approval Summary --}}
    <div class="row g-3">

        {{-- Pending --}}
        <div class="col-xl-3 col-md-6">

           <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small mb-1">
                                Pending Approval
                            </div>

                            <h3 class="fw-bold mb-0">
                                {{ $pendingCount }}
                            </h3>

                        </div>

                        <div class="text-warning fs-1">
                            <i class="bi bi-hourglass-split"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Approved --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small mb-1">
                                Approved
                            </div>

                            <h3 class="fw-bold mb-0">
                                {{ $approvedCount }}
                            </h3>

                        </div>

                        <div class="text-success fs-1">
                            <i class="bi bi-check-circle"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Declined --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small mb-1">
                                Declined
                            </div>

                            <h3 class="fw-bold mb-0">
                                {{ $declinedCount }}
                            </h3>

                        </div>

                        <div class="text-danger fs-1">
                            <i class="bi bi-x-circle"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Cancelled --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small mb-1">
                                Cancelled
                            </div>

                            <h3 class="fw-bold mb-0">
                                {{ $cancelledCount }}
                            </h3>

                        </div>

                        <div class="text-secondary fs-1">
                            <i class="bi bi-slash-circle"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>
    @endif


    {{-- Error Message --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">

            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>
    @endif

    {{-- Approval Filters --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-body">

        <div class="d-flex flex-wrap gap-2">

            {{-- All --}}
            <a
                href="{{ route('request-approvals.index', ['status' => 'All']) }}"
                class="btn {{ $filter === 'All' ? 'btn-dark' : 'btn-outline-secondary' }}">

                <i class="bi bi-grid me-1"></i>
                All

            </a>


            {{-- Pending --}}
            <a
                href="{{ route('request-approvals.index', ['status' => 'Pending']) }}"
                class="btn {{ $filter === 'Pending' ? 'btn-warning' : 'btn-outline-warning' }}">

                <i class="bi bi-hourglass-split me-1"></i>
                Pending

                <span class="badge bg-dark ms-1">
                    {{ $pendingCount }}
                </span>

            </a>


            {{-- Approved --}}
            <a
                href="{{ route('request-approvals.index', ['status' => 'Approved']) }}"
                class="btn {{ $filter === 'Approved' ? 'btn-success' : 'btn-outline-success' }}">

                <i class="bi bi-check-circle me-1"></i>
                Approved

                <span class="badge bg-success ms-1">
                    {{ $approvedCount }}
                </span>

            </a>


            {{-- Declined --}}
            <a
                href="{{ route('request-approvals.index', ['status' => 'Declined']) }}"
                class="btn {{ $filter === 'Declined' ? 'btn-danger' : 'btn-outline-danger' }}">

                <i class="bi bi-x-circle me-1"></i>
                Declined

                <span class="badge bg-danger ms-1">
                    {{ $declinedCount }}
                </span>

            </a>


            {{-- Cancelled --}}
            <a
                href="{{ route('request-approvals.index', ['status' => 'Cancelled']) }}"
                class="btn {{ $filter === 'Cancelled' ? 'btn-secondary' : 'btn-outline-secondary' }}">

                <i class="bi bi-slash-circle me-1"></i>
                Cancelled

                <span class="badge bg-secondary ms-1">
                    {{ $cancelledCount }}
                </span>

            </a>

        </div>

    </div>

</div>


    {{-- No Pending Requests --}}
    @if($requests->isEmpty())

        <div class="card border-0 shadow-sm">

            <div class="card-body text-center py-5">

                <i class="bi bi-check-circle text-success"
                   style="font-size: 4rem;">
                </i>

                <h4 class="mt-3">
                    No Pending Approvals
                </h4>

                <p class="text-muted mb-0">
                    There are currently no incoming requests waiting for approval.
                </p>

            </div>

        </div>

    @else

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>

            <h5 class="fw-bold mb-1">

    @if($filter === 'All')
        All Requests

    @elseif($filter === 'Pending')
        Pending Requests

    @elseif($filter === 'Approved')
        Approved Requests

    @elseif($filter === 'Declined')
        Declined Requests

    @elseif($filter === 'Cancelled')
        Cancelled Requests

    @endif

</h5>

<small class="text-muted">

    @if($filter === 'Pending')
        These requests are waiting for an administrator decision.

    @elseif($filter === 'Approved')
        These requests have been approved by an administrator.

    @elseif($filter === 'Declined')
        These requests have been declined.

    @elseif($filter === 'Cancelled')
        These requests have been cancelled.

    @else
        View all requests and their approval status.

    @endif

</small>

        </div>

    </div>

    <div class="row g-4">

    @foreach($requests as $documentRequest)

        @php
            $priority = $documentRequest->priority ?? 'Normal';

            $priorityClass = match($priority) {
                'Urgent' => 'border-danger border-2',
                'High' => 'border-warning border-2',
                default => 'border-0',
            };

            $priorityBadge = match($priority) {
                'Urgent' => 'bg-danger text-white',
                'High' => 'bg-warning text-dark',
                'Normal' => 'bg-primary text-white',
                'Low' => 'bg-secondary text-white',
                default => 'bg-secondary text-white',
            };

            $priorityIcon = match($priority) {
                'Urgent' => 'bi-exclamation-triangle-fill',
                'High' => 'bi-exclamation-circle-fill',
                'Normal' => 'bi-dash-circle-fill',
                'Low' => 'bi-arrow-down-circle-fill',
                default => 'bi-question-circle-fill',
            };
        @endphp

        <div class="col-xl-4 col-lg-6">

            <div class="card shadow-sm h-100 {{ $priorityClass }}">
                

                        {{-- Card Header --}}
                        <div class="card-header bg-white border-bottom py-3">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>
                                    <h5 class="fw-bold mb-1">
                                        {{ $documentRequest->control_no }}
                                    </h5>

                                    <small class="text-muted">
                                        Submitted
                                        {{ $documentRequest->created_at?->format('F d, Y h:i A') }}
                                    </small>
                                </div>

                                @if($documentRequest->approval_status === 'Pending')

    <span class="badge bg-warning text-dark">
        Pending Approval
    </span>

@elseif($documentRequest->approval_status === 'Approved')

    <span class="badge bg-success">
        Approved
    </span>

@elseif($documentRequest->approval_status === 'Declined')

    <span class="badge bg-danger fs-6 px-3 py-2">
        <i class="bi bi-x-circle me-1"></i>
        Declined
    </span>

    @if(auth()->user()->role === 'Admin')
        <button
            type="button"
            class="btn btn-warning"
            data-bs-toggle="modal"
            data-bs-target="#restoreForApprovalModal{{ $documentRequest->id }}">

            <i class="bi bi-arrow-counterclockwise me-1"></i>
            Restore

        </button>
    @endif

@elseif($documentRequest->approval_status === 'Cancelled')

    <span class="badge bg-secondary fs-6 px-3 py-2">
        <i class="bi bi-slash-circle me-1"></i>
        Cancelled
    </span>

    @if(auth()->user()->role === 'Admin')
        <button
            type="button"
            class="btn btn-warning"
            data-bs-toggle="modal"
            data-bs-target="#restoreForApprovalModal{{ $documentRequest->id }}">

            <i class="bi bi-arrow-counterclockwise me-1"></i>
            Restore

        </button>
    @endif

@endif

                            </div>

                        </div>


                        {{-- Card Body --}}
<div class="card-body p-4">

    {{-- Basic Request Information --}}
    <div class="row g-4">

        {{-- Requester --}}
        <div class="col-md-6">
            <small class="text-muted d-block mb-1">
                Requester
            </small>

            <strong>
                {{ $documentRequest->createdBy?->name ?? 'N/A' }}
            </strong>
        </div>

        {{-- Department --}}
        <div class="col-md-6">
            <small class="text-muted d-block mb-1">
                Department
            </small>

            <strong>
                {{ $documentRequest->organization?->name ?? 'N/A' }}
            </strong>
        </div>

        {{-- Service --}}
        <div class="col-12">
            <small class="text-muted d-block mb-1">
                Service
            </small>

            <strong>
                {{ $documentRequest->services->first()?->name ?? 'N/A' }}
            </strong>
        </div>

        {{-- Subject --}}
        @if(!empty($documentRequest->subject))
            <div class="col-12">
                <small class="text-muted d-block mb-1">
                    Subject
                </small>

                <div>
                    {{ $documentRequest->subject }}
                </div>
            </div>
        @endif

    </div>


    {{-- Request Details --}}
    <div class="row g-4 mt-1 mb-4">

        {{-- Request Type --}}
        <div class="col-6">
            <div class="small text-muted mb-1">
                <i class="bi bi-file-earmark-text me-1"></i>
                Request Type
            </div>

            <div class="fw-semibold">
                {{ $documentRequest->request_type ?? '-' }}
            </div>
        </div>

        {{-- Due Date --}}
<div class="col-6">
    <div class="small text-muted mb-1">
        <i class="bi bi-calendar-event me-1"></i>
        Due Date
    </div>

    @if($documentRequest->due_date)

        @php
            $dueDate = $documentRequest->due_date->startOfDay();
            $today = today();

            $daysUntilDue = $today->diffInDays($dueDate, false);
        @endphp

        <div class="fw-semibold">
            {{ $documentRequest->due_date->format('M d, Y') }}
        </div>

        <div class="mt-1">

            {{-- Overdue --}}
            @if($daysUntilDue < 0)

                <span class="badge rounded-pill bg-danger px-2 py-1">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    Overdue by {{ abs($daysUntilDue) }}
                    {{ abs($daysUntilDue) === 1 ? 'day' : 'days' }}
                </span>

            {{-- Due Today --}}
            @elseif($daysUntilDue === 0)

                <span class="badge rounded-pill bg-warning text-dark px-2 py-1">
                    <i class="bi bi-exclamation-circle-fill me-1"></i>
                    Due Today
                </span>

            {{-- Due Soon --}}
            @elseif($daysUntilDue <= 3)

                <span class="badge rounded-pill bg-warning text-dark px-2 py-1">
                    <i class="bi bi-clock-fill me-1"></i>
                    Due in {{ $daysUntilDue }}
                    {{ $daysUntilDue === 1 ? 'day' : 'days' }}
                </span>

            {{-- Future --}}
            @else

                <span class="badge rounded-pill bg-success px-2 py-1">
                    <i class="bi bi-calendar-check-fill me-1"></i>
                    Due in {{ $daysUntilDue }}
                    {{ $daysUntilDue === 1 ? 'day' : 'days' }}
                </span>

            @endif

        </div>

    @else

        <div class="fw-semibold text-muted">
            -
        </div>

    @endif
</div>

    </div>


    {{-- Priority --}}
    <div class="mb-4">

        <div class="small text-muted mb-2">
            <i class="bi bi-flag-fill me-1"></i>
            Priority
        </div>

        <span class="badge {{ $priorityBadge }} px-3 py-2">
            <i class="bi {{ $priorityIcon }} me-1"></i>
            {{ strtoupper($priority) }}
        </span>

    </div>


    {{-- Description --}}
    <div>

        <div class="small text-muted mb-2">
            <i class="bi bi-text-paragraph me-1"></i>
            Description
        </div>

        <div class="small text-muted">
            {{ \Illuminate\Support\Str::limit(
                $documentRequest->description ?? 'No description provided.',
                120
            ) }}
        </div>

    </div>
{{-- Approval History --}}
@if($documentRequest->approval_status !== 'Pending')

    <div class="border-top pt-3 mt-4">

        <div class="small text-muted mb-2">
            <i class="bi bi-person-check me-1"></i>
            Approval Review
        </div>

        <div class="row g-3">

            {{-- Reviewed By --}}
            <div class="col-md-6">

                <div class="small text-muted">
                    Reviewed By
                </div>

                <div class="fw-semibold">
                    {{ $documentRequest->approvalReviewer?->name ?? 'N/A' }}
                </div>

            </div>

            {{-- Reviewed Date --}}
            <div class="col-md-6">

                <div class="small text-muted">
                    Reviewed Date
                </div>

                <div class="fw-semibold">
                    {{ $documentRequest->approval_reviewed_at?->format('M d, Y h:i A') ?? '-' }}
                </div>

            </div>

        </div>

        {{-- Review Note --}}
        @if($documentRequest->approval_review_note)

            <div class="mt-3">

                <div class="small text-muted mb-1">
                    <i class="bi bi-chat-left-text me-1"></i>
                    Review Note
                </div>

                <div class="small">
                    {{ $documentRequest->approval_review_note }}
                </div>

            </div>

        @endif

    </div>

@endif
</div>
                        {{-- Card Footer --}}
                        <div class="card-footer bg-white border-top p-3">

                            <div class="d-flex flex-wrap gap-1">

                                {{-- View Full Request --}}
                                <a
                                    href="{{ route('requests.show', $documentRequest) }}"
                                    class="btn btn-sm btn-outline-secondary">

                                    <i class="bi bi-eye me-1"></i>
                                    View Details

                                </a>


                               @if($documentRequest->approval_status === 'Pending')

    {{-- Approve --}}
<button
    type="button"
    class="btn btn-success"
    data-bs-toggle="modal"
    data-bs-target="#approveRequestModal{{ $documentRequest->id }}">

    <i class="bi bi-check-lg me-1"></i>
    Approve

</button>


    {{-- Decline --}}
    <button
        type="button"
       class="btn btn-danger"
        data-bs-toggle="modal"
        data-bs-target="#declineRequestModal{{ $documentRequest->id }}">

        <i class="bi bi-x-lg me-1"></i>
        Decline

    </button>

    {{-- Approve Modal --}}
<div
    class="modal fade"
    id="approveRequestModal{{ $documentRequest->id }}"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                method="POST"
                action="{{ route('requests.approve', $documentRequest) }}">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">

                        <i class="bi bi-check-circle me-2 text-success"></i>

                        Approve Request

                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="alert alert-success">

                        <i class="bi bi-check-circle me-2"></i>

                        You are about to approve this request.

                    </div>

                    <p class="mb-3">

                        Are you sure you want to approve

                        <strong>
                            {{ $documentRequest->control_no }}
                        </strong>?

                    </p>

                    <div class="mb-3">

                        <label class="form-label">
                            Review Note
                            <span class="text-muted">(Optional)</span>
                        </label>

                        <textarea
                            name="approval_review_note"
                            class="form-control"
                            rows="3"
                            maxlength="1000"
                            placeholder="Enter an optional approval note..."></textarea>

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
                        class="btn btn-success">

                        <i class="bi bi-check-lg me-1"></i>

                        Confirm Approval

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


    {{-- Cancel --}}
    <button
        type="button"
        class="btn btn-sm btn-secondary"
        data-bs-toggle="modal"
        data-bs-target="#cancelRequestModal{{ $documentRequest->id }}">

        <i class="bi bi-slash-circle me-1"></i>
        Cancel

    </button>

@elseif($documentRequest->approval_status === 'Approved')

    <span class="badge bg-success fs-6 px-3 py-2">
        <i class="bi bi-check-circle me-1"></i>
        Approved
    </span>

@elseif($documentRequest->approval_status === 'Declined')

    <span class="badge bg-danger fs-6 px-3 py-2">
        <i class="bi bi-x-circle me-1"></i>
        Declined
    </span>

@elseif($documentRequest->approval_status === 'Cancelled')

    <span class="badge bg-secondary fs-6 px-3 py-2">
        <i class="bi bi-slash-circle me-1"></i>
        Cancelled
    </span>

@endif



                            </div>

                        </div>

                    </div>

                </div>


                {{-- Decline Modal --}}
                <div
                    class="modal fade"
                    id="declineRequestModal{{ $documentRequest->id }}"
                    tabindex="-1"
                    aria-hidden="true">

                    <div class="modal-dialog">

                        <div class="modal-content">

                            <form
                                method="POST"
                                action="{{ route('requests.decline', $documentRequest) }}">

                                @csrf

                                <div class="modal-header">

                                    <h5 class="modal-title">
                                        Decline Request
                                    </h5>

                                    <button
                                        type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal">
                                    </button>

                                </div>

                                <div class="modal-body">

                                    <p>
                                        Are you sure you want to decline
                                        <strong>
                                            {{ $documentRequest->control_no }}
                                        </strong>?
                                    </p>

                                    <label class="form-label">
                                        Reason
                                    </label>

                                    <textarea
                                        name="approval_review_note"
                                        class="form-control"
                                        rows="4"
                                        required
                                        placeholder="Enter the reason for declining this request..."></textarea>

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
                                        class="btn btn-danger">

                                        <i class="bi bi-x-lg me-1"></i>
                                        Confirm Decline

                                    </button>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>


                {{-- Cancel Modal --}}
                <div
                    class="modal fade"
                    id="cancelRequestModal{{ $documentRequest->id }}"
                    tabindex="-1"
                    aria-hidden="true">

                    <div class="modal-dialog">

                        <div class="modal-content">

                            <form
                                method="POST"
                                action="{{ route('requests.cancel', $documentRequest) }}">

                                @csrf

                                <div class="modal-header">

                                    <h5 class="modal-title">
                                        Cancel Request
                                    </h5>

                                    <button
                                        type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal">
                                    </button>

                                </div>

                                <div class="modal-body">

                                    <p>
                                        Are you sure you want to cancel
                                        <strong>
                                            {{ $documentRequest->control_no }}
                                        </strong>?
                                    </p>

                                    <label class="form-label">
                                        Reason
                                    </label>

                                    <textarea
                                        name="approval_review_note"
                                        class="form-control"
                                        rows="4"
                                        required
                                        placeholder="Enter the reason for cancelling this request..."></textarea>

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
                                        Confirm Cancellation

                                    </button>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>
{{-- Restore for Approval Modal --}}
<div
    class="modal fade"
    id="restoreForApprovalModal{{ $documentRequest->id }}"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                method="POST"
                action="{{ route('requests.restore-for-approval', $documentRequest) }}">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">
                        <i class="bi bi-arrow-counterclockwise me-2"></i>
                        Restore for Approval
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="alert alert-warning">

                        <i class="bi bi-exclamation-triangle me-2"></i>

                        This request will be returned to
                        <strong>Pending Approval</strong>.

                    </div>

                    <p>
                        Are you sure you want to restore
                        <strong>
                            {{ $documentRequest->control_no }}
                        </strong>
                        for approval?
                    </p>

                    <div class="mb-3">

                        <label class="form-label">
                            Reason
                        </label>

                        <textarea
                            name="approval_review_note"
                            class="form-control"
                            rows="4"
                            required
                            placeholder="Enter the reason for restoring this request for approval..."></textarea>

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
            @endforeach

        </div>

    @endif

</div>

@endsection