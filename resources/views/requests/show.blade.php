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

                {{-- Incoming --}}
                @case('Incoming')

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

                            <i class="bi bi-person-check-fill"></i>

                            Assign Request

                        </button>

                    </form>

                    @break

                {{-- Assigned --}}
                @case('Assigned')

                    <form
                        method="POST"
                        action="{{ route('requests.start', $documentRequest) }}">

                        @csrf

                        <button
                            class="btn btn-success w-100">

                            <i class="bi bi-play-circle"></i>

                            Start Work

                        </button>

                    </form>

                    @break

                {{-- Ongoing --}}
                @case('Ongoing')

                    <form
                        method="POST"
                        action="{{ route('requests.complete', $documentRequest) }}">

                        @csrf

                        <button
                            class="btn btn-primary w-100">

                            <i class="bi bi-check-circle"></i>

                            Mark Completed

                        </button>

                    </form>

                    @break

                {{-- Completed --}}
                @case('Completed')

                    <form
                        method="POST"
                        action="{{ route('requests.archive', $documentRequest) }}">

                        @csrf

                        <button
                            class="btn btn-dark w-100">

                            <i class="bi bi-archive"></i>

                            Archive Request

                        </button>

                    </form>

                    @break

                {{-- Archived --}}
@case('Archived')

    <div class="alert alert-secondary mb-3">

        This request is currently archived.

    </div>

    <form
        method="POST"
        action="{{ route('requests.restore', $documentRequest) }}">

        @csrf

        <button
            type="submit"
            class="btn btn-success w-100"
            onclick="return confirm('Restore this request and move it back to Incoming?');">

            <i class="bi bi-arrow-counterclockwise me-1"></i>

            Restore Request

        </button>

    </form>

    @break

            @endswitch

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

            <a
    href="{{ route('requests.edit', ['documentRequest' => $documentRequest]) }}"
    class="btn btn-primary">

    <i class="bi bi-pencil"></i>

    Edit Request

</a>

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
        </div>

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
    @media (max-width: 768px) {

        .activity-header {
            display: block;
        }

        .activity-time {
            display: block;
            margin-top: 3px;
        }

        .activity-user {
            display: block;
            margin-left: 0;
            margin-top: 2px;
        }

    }

</style>

</div>

    </div>

</div>

    </div>
@endsection
