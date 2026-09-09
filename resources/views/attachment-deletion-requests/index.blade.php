@extends('layouts.master')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="mb-1">
                Attachment Deletion Requests
            </h2>

            <p class="text-muted mb-0">
                Review requests submitted by staff for file deletion.
            </p>
        </div>

    </div>


    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
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
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- ========================================================= --}}
    {{-- PENDING REQUESTS --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-warning-subtle">

            <div class="d-flex align-items-center">

                <i class="bi bi-hourglass-split me-2"></i>

                <strong>
                    Pending Deletion Requests
                </strong>

            </div>

        </div>


        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>

                        <tr>

                            <th>File</th>

                            <th>Requested By</th>

                            <th>Reason</th>

                            <th>Date Requested</th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @php
                            $pendingRequests = $requests->where('status', 'Pending');
                        @endphp


                        @forelse($pendingRequests as $deletionRequest)

                            <tr>

                                {{-- File --}}
                                <td>

                                    <div class="fw-semibold">

                                        {{ $deletionRequest->file_name
                                            ?? $deletionRequest->attachment?->file_name
                                            ?? 'File no longer exists' }}

                                    </div>

                                    @if($deletionRequest->attachment)

                                        <small class="text-muted">

                                            {{ $deletionRequest->attachment->attachment_type }}

                                        </small>

                                    @endif

                                </td>


                                {{-- Requested By --}}
                                <td>

                                    {{ $deletionRequest->requester?->name ?? 'Unknown User' }}

                                </td>


                                {{-- Reason --}}
                                <td style="max-width: 300px;">

                                    {{ $deletionRequest->reason }}

                                </td>


                                {{-- Date --}}
                                <td>

                                    {{ $deletionRequest->created_at->format('M d, Y h:i A') }}

                                </td>


                                {{-- Actions --}}
                                <td class="text-end">

                                    {{-- Approve --}}
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-success"
                                        data-bs-toggle="modal"
                                        data-bs-target="#approveModal{{ $deletionRequest->id }}">

                                        <i class="bi bi-check-lg"></i>
                                        Approve

                                    </button>


                                    {{-- Reject --}}
<button
    type="button"
    class="btn btn-sm btn-outline-danger"
    data-bs-toggle="modal"
    data-bs-target="#rejectModal{{ $deletionRequest->id }}">

    <i class="bi bi-x-lg"></i>
    Reject

</button>

{{-- Reject Confirmation Modal --}}
<div
    class="modal fade"
    id="rejectModal{{ $deletionRequest->id }}"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">

                    <i class="bi bi-x-circle text-danger"></i>

                    Reject Deletion Request

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>


            <form
                method="POST"
                action="{{ route('attachment-deletion-requests.reject', $deletionRequest) }}">

                @csrf

                <div class="modal-body">

                    <p>
                        Are you sure you want to reject this deletion request?
                    </p>


                    <div class="alert alert-warning">

                        <strong>File:</strong><br>

                        {{ $deletionRequest->file_name
                            ?? $deletionRequest->attachment?->file_name
                            ?? 'File no longer exists' }}

                    </div>


                    <p class="text-muted">

                        <strong>Requested By:</strong><br>

                        {{ $deletionRequest->requester?->name ?? 'Unknown User' }}

                    </p>


                    <p class="text-muted">

                        <strong>Original Reason:</strong><br>

                        {{ $deletionRequest->reason }}

                    </p>


                    <div class="mb-3">

                        <label
                            for="review_note{{ $deletionRequest->id }}"
                            class="form-label fw-semibold">

                            Reason for Rejection
                            <span class="text-danger">*</span>

                        </label>

                        <textarea
                            name="review_note"
                            id="review_note{{ $deletionRequest->id }}"
                            class="form-control"
                            rows="4"
                            placeholder="Please explain why this deletion request is being rejected."
                            required></textarea>

                    </div>


                    <div class="alert alert-danger mb-0">

                        <i class="bi bi-exclamation-triangle"></i>

                        The attachment will <strong>NOT</strong> be deleted.

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

                        <i class="bi bi-x-lg"></i>

                        Reject Request

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


                                    {{-- Approve Confirmation Modal --}}
                                    <div
                                        class="modal fade"
                                        id="approveModal{{ $deletionRequest->id }}"
                                        tabindex="-1"
                                        aria-hidden="true">

                                        <div class="modal-dialog modal-dialog-centered">

                                            <div class="modal-content">


                                                {{-- Modal Header --}}
                                                <div class="modal-header">

                                                    <h5 class="modal-title">

                                                        <i class="bi bi-check-circle text-success"></i>

                                                        Approve Deletion Request

                                                    </h5>

                                                    <button
                                                        type="button"
                                                        class="btn-close"
                                                        data-bs-dismiss="modal">
                                                    </button>

                                                </div>


                                                {{-- Modal Body --}}
                                                <div class="modal-body">

                                                    <p>

                                                        Are you sure you want to approve this deletion request?

                                                    </p>


                                                    <div class="alert alert-warning">

                                                        <strong>File:</strong><br>

                                                        {{ $deletionRequest->file_name
                                                            ?? $deletionRequest->attachment?->file_name
                                                            ?? 'File no longer exists' }}

                                                    </div>


                                                    <p class="text-muted mb-0">

                                                        <strong>Requested By:</strong><br>

                                                        {{ $deletionRequest->requester?->name ?? 'Unknown User' }}

                                                    </p>


                                                    <p class="text-muted mt-3 mb-0">

                                                        <strong>Reason:</strong><br>

                                                        {{ $deletionRequest->reason }}

                                                    </p>


                                                    <div class="alert alert-danger mt-3 mb-0">

                                                        <i class="bi bi-exclamation-triangle"></i>

                                                        <strong>Warning:</strong>

                                                        Approving this request will permanently delete the attachment.

                                                    </div>

                                                </div>


                                                {{-- Modal Footer --}}
                                                <div class="modal-footer">

                                                    <button
                                                        type="button"
                                                        class="btn btn-secondary"
                                                        data-bs-dismiss="modal">

                                                        Cancel

                                                    </button>


                                                    <form
                                                        method="POST"
                                                        action="{{ route('attachment-deletion-requests.approve', $deletionRequest) }}"
                                                        class="d-inline">

                                                        @csrf

                                                        <button
                                                            type="submit"
                                                            class="btn btn-success">

                                                            <i class="bi bi-check-lg"></i>

                                                            Approve Deletion

                                                        </button>

                                                    </form>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="text-center text-muted py-5">

                                    <i class="bi bi-check-circle fs-1 d-block mb-2"></i>

                                    No pending deletion requests.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- REQUEST HISTORY --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm">

        <div class="card-header">

            <div class="d-flex align-items-center">

                <i class="bi bi-clock-history me-2"></i>

                <strong>
                    Deletion Request History
                </strong>

            </div>

        </div>


        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>

                        <tr>

                            <th>File</th>

                            <th>Requested By</th>

                            <th>Reason</th>

                            <th>Status</th>
<th>Reviewed By</th>
<th>Review Note</th>
<th>Date Reviewed</th>

                        </tr>

                    </thead>


                    <tbody>

                        @php
                            $historyRequests = $requests->whereIn('status', ['Approved', 'Rejected']);
                        @endphp


                        @forelse($historyRequests as $deletionRequest)

                            <tr>

                                {{-- File --}}
                                <td>

                                    <div class="fw-semibold">

                                        {{ $deletionRequest->file_name
                                            ?? $deletionRequest->attachment?->file_name
                                            ?? 'File no longer exists' }}

                                    </div>

                                    @if($deletionRequest->status === 'Approved' && !$deletionRequest->attachment)

                                        <small class="text-muted">

                                            Attachment deleted

                                        </small>

                                    @elseif($deletionRequest->attachment)

                                        <small class="text-muted">

                                            {{ $deletionRequest->attachment->attachment_type }}

                                        </small>

                                    @endif

                                </td>


                                {{-- Requested By --}}
                                <td>

                                    {{ $deletionRequest->requester?->name ?? 'Unknown User' }}

                                </td>


                                {{-- Reason --}}
                                <td style="max-width: 300px;">

                                    {{ $deletionRequest->reason }}

                                </td>


                                {{-- Status --}}
                                <td>

                                    @if($deletionRequest->status === 'Approved')

                                        <span class="badge bg-success">

                                            <i class="bi bi-check-lg"></i>

                                            Approved

                                        </span>

                                    @else

                                        <span class="badge bg-danger">

                                            <i class="bi bi-x-lg"></i>

                                            Rejected

                                        </span>

                                    @endif

                                </td>


                                {{-- Reviewed By --}}
                                <td>

                                    {{ $deletionRequest->reviewer?->name ?? 'Unknown User' }}

                                </td>

                                {{-- Review Note --}}
<td style="max-width: 300px;">
    @if($deletionRequest->review_note)
        <span title="{{ $deletionRequest->review_note }}">
            {{ $deletionRequest->review_note }}
        </span>
    @else
        <span class="text-muted">—</span>
    @endif
</td>

                                {{-- Date Reviewed --}}
                                <td>

                                    {{ $deletionRequest->reviewed_at
                                        ? $deletionRequest->reviewed_at->format('M d, Y h:i A')
                                        : '—' }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center text-muted py-5">

                                    <i class="bi bi-clock-history fs-1 d-block mb-2"></i>

                                    No completed deletion requests yet.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection