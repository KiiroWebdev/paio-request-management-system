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

                    <div class="alert alert-secondary mb-0">

                        This request has already been archived.

                    </div>

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

            <button
                class="btn btn-outline-dark"
                disabled>

                <i class="bi bi-printer"></i>

                Print

            </button>

        </div>

    </div>

</div>

    </div>  
@endsection