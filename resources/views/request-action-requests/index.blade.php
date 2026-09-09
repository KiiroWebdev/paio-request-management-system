@extends('layouts.master')

@section('title', 'Request Action Requests')

@section('content')

<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">
                <i class="bi bi-check2-square me-2"></i>
                Request Action Requests
            </h3>
            <p class="text-muted mb-0">
                Review archive and restore requests submitted by staff.
            </p>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}

            <button type="button"
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

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- Pending Requests --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white py-3">
            <h5 class="mb-0">
                <i class="bi bi-hourglass-split me-2"></i>
                Pending Requests
            </h5>
        </div>

        <div class="card-body p-0">

            @php
                $pendingRequests = $requests->where('status', 'Pending');
            @endphp

            @if($pendingRequests->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">
                            <tr>
                                <th>Request</th>
                                <th>Action</th>
                                <th>Reason</th>
                                <th>Requested By</th>
                                <th>Date</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>

                        <tbody>

                        @foreach($pendingRequests as $actionRequest)

                            <tr>

                                {{-- Request --}}
                                <td>
                                    @if($actionRequest->documentRequest)
                                        <a href="{{ route('requests.show', $actionRequest->documentRequest) }}"
                                           class="fw-semibold text-decoration-none">
                                            {{ $actionRequest->documentRequest->control_no }}
                                        </a>
                                    @else
                                        <span class="text-muted">
                                            Request no longer exists
                                        </span>
                                    @endif
                                </td>


                                {{-- Action --}}
                                <td>

                                    @if($actionRequest->action === 'Archive')

                                        <span class="badge bg-dark">
                                            <i class="bi bi-archive me-1"></i>
                                            Archive
                                        </span>

                                    @elseif($actionRequest->action === 'Restore')

                                        <span class="badge bg-success">
                                            <i class="bi bi-arrow-counterclockwise me-1"></i>
                                            Restore
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            {{ $actionRequest->action }}
                                        </span>

                                    @endif

                                </td>


                                {{-- Reason --}}
                                <td style="min-width: 250px;">
                                    {{ $actionRequest->reason }}
                                </td>


                                {{-- Requested By --}}
                                <td>
                                    {{ $actionRequest->requester?->name ?? 'Unknown' }}
                                </td>


                                {{-- Date --}}
                                <td>
                                    {{ $actionRequest->created_at?->format('M d, Y h:i A') }}
                                </td>


                                {{-- Actions --}}
                                <td class="text-end">

                                    {{-- Approve --}}
                                    <form method="POST"
                                          action="{{ route('request-action-requests.approve', $actionRequest) }}"
                                          class="d-inline">

                                        @csrf

                                        <button type="submit"
                                                class="btn btn-success btn-sm"
                                                onclick="return confirm('Are you sure you want to approve this request?');">

                                            <i class="bi bi-check-lg me-1"></i>
                                            Approve

                                        </button>

                                    </form>


                                    {{-- Reject --}}
                                    <button type="button"
                                            class="btn btn-outline-danger btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#rejectModal{{ $actionRequest->id }}">

                                        <i class="bi bi-x-lg me-1"></i>
                                        Reject

                                    </button>

                                </td>

                            </tr>


                            {{-- Reject Modal --}}
                            <div class="modal fade"
                                 id="rejectModal{{ $actionRequest->id }}"
                                 tabindex="-1"
                                 aria-hidden="true">

                                <div class="modal-dialog">

                                    <div class="modal-content">

                                        <div class="modal-header">

                                            <h5 class="modal-title">
                                                <i class="bi bi-x-circle me-2"></i>
                                                Reject Request
                                            </h5>

                                            <button type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal">
                                            </button>

                                        </div>


                                        <form method="POST"
                                              action="{{ route('request-action-requests.reject', $actionRequest) }}">

                                            @csrf

                                            <div class="modal-body">

                                                <div class="mb-3">

                                                    <label class="form-label fw-semibold">
                                                        Document Request
                                                    </label>

                                                    <div class="form-control bg-light">

                                                        {{ $actionRequest->documentRequest?->control_no ?? 'Request no longer exists' }}

                                                    </div>

                                                </div>


                                                <div class="mb-3">

                                                    <label class="form-label fw-semibold">
                                                        Action
                                                    </label>

                                                    <div class="form-control bg-light">

                                                        {{ $actionRequest->action }}

                                                    </div>

                                                </div>


                                                <div class="mb-3">

                                                    <label class="form-label fw-semibold">
                                                        Review Note
                                                    </label>

                                                    <textarea name="review_note"
                                                              class="form-control"
                                                              rows="4"
                                                              placeholder="Please explain why this request is being rejected."
                                                              required></textarea>

                                                </div>

                                            </div>


                                            <div class="modal-footer">

                                                <button type="button"
                                                        class="btn btn-secondary"
                                                        data-bs-dismiss="modal">

                                                    Cancel

                                                </button>


                                                <button type="submit"
                                                        class="btn btn-danger">

                                                    <i class="bi bi-x-lg me-1"></i>
                                                    Reject Request

                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <i class="bi bi-check-circle display-5 text-success"></i>

                    <h5 class="mt-3">
                        No pending requests
                    </h5>

                    <p class="text-muted mb-0">
                        There are currently no archive or restore requests waiting for review.
                    </p>

                </div>

            @endif

        </div>

    </div>


    {{-- Request History --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3">

            <h5 class="mb-0">
                <i class="bi bi-clock-history me-2"></i>
                Request History
            </h5>

        </div>


        <div class="card-body p-0">

            @php
                $historyRequests = $requests->whereIn('status', ['Approved', 'Rejected']);
            @endphp


            @if($historyRequests->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>
                                <th>Request</th>
                                <th>Action</th>
                                <th>Status</th>
                                <th>Requested By</th>
                                <th>Reviewed By</th>
                                <th>Review Note</th>
                                <th>Date</th>
                            </tr>

                        </thead>


                        <tbody>

                        @foreach($historyRequests as $actionRequest)

                            <tr>

                                {{-- Request --}}
                                <td>

                                    @if($actionRequest->documentRequest)

                                        <a href="{{ route('requests.show', $actionRequest->documentRequest) }}"
                                           class="fw-semibold text-decoration-none">

                                            {{ $actionRequest->documentRequest->control_no }}

                                        </a>

                                    @else

                                        <span class="text-muted">
                                            Request no longer exists
                                        </span>

                                    @endif

                                </td>


                                {{-- Action --}}
                                <td>

                                    @if($actionRequest->action === 'Archive')

                                        <span class="badge bg-dark">
                                            <i class="bi bi-archive me-1"></i>
                                            Archive
                                        </span>

                                    @else

                                        <span class="badge bg-success">
                                            <i class="bi bi-arrow-counterclockwise me-1"></i>
                                            Restore
                                        </span>

                                    @endif

                                </td>


                                {{-- Status --}}
                                <td>

                                    @if($actionRequest->status === 'Approved')

                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Approved
                                        </span>

                                    @else

                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-circle me-1"></i>
                                            Rejected
                                        </span>

                                    @endif

                                </td>


                                {{-- Requested By --}}
                                <td>
                                    {{ $actionRequest->requester?->name ?? 'Unknown' }}
                                </td>


                                {{-- Reviewed By --}}
                                <td>
                                    {{ $actionRequest->reviewer?->name ?? '—' }}
                                </td>


                                {{-- Review Note --}}
                                <td style="max-width: 300px;">

                                    @if($actionRequest->review_note)

                                        <span title="{{ $actionRequest->review_note }}">
                                            {{ \Illuminate\Support\Str::limit($actionRequest->review_note, 80) }}
                                        </span>

                                    @else

                                        <span class="text-muted">—</span>

                                    @endif

                                </td>


                                {{-- Date --}}
                                <td>
                                    {{ $actionRequest->reviewed_at?->format('M d, Y h:i A') ?? '—' }}
                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <i class="bi bi-clock-history display-5 text-muted"></i>

                    <h5 class="mt-3">
                        No request history
                    </h5>

                    <p class="text-muted mb-0">
                        Approved and rejected requests will appear here.
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection