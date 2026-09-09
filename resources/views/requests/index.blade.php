@extends('layouts.master')

@section('page-title', $pageTitle ?? 'Request Management')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <x-admin.page-header
    :title="$pageTitle ?? 'Request Management'"
    subtitle="Manage document requests">

        <a href="{{ route('request-wizard.services') }}"
           class="btn btn-primary">

            <i class="bi bi-plus-circle"></i>

            New Request

        </a>

    </x-admin.page-header>

    <x-admin.breadcrumb
    :items="['Request Management', $pageTitle ?? 'Request Management']"/>

    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show mb-4">

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif
{{-- Request Filters --}}
<div class="card shadow-sm border-0 mb-4">

    <div class="card-header bg-white">

        <div class="d-flex align-items-center">

            <i class="bi bi-funnel-fill text-primary me-2"></i>

            <h5 class="mb-0">

                Search & Filters

            </h5>

        </div>

    </div>

    <div class="card-body">

        <form method="GET"
              action="{{ route('requests.index') }}">

            <div class="row g-3">

                {{-- Search --}}
                <div class="col-lg-4">

                    <label class="form-label fw-semibold">

                        Search

                    </label>

                    <div class="input-group">

                        <span class="input-group-text">

                            <i class="bi bi-search"></i>

                        </span>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Control No., title, subject, or requestor..."
                            value="{{ request('search') }}">

                    </div>

                </div>

                {{-- Organization --}}
                <div class="col-lg-2">

                    <label class="form-label fw-semibold">

                        Organization

                    </label>

                    <select
                        name="organization"
                        class="form-select">

                        <option value="">

                            All Organizations

                        </option>

                        @foreach($organizations as $organization)

                            <option
                                value="{{ $organization->id }}"
                                @selected(request('organization') == $organization->id)>

                                {{ $organization->name }}

                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- Service --}}
                <div class="col-lg-2">

                    <label class="form-label fw-semibold">

                        Service

                    </label>

                    <select
                        name="service"
                        class="form-select">

                        <option value="">

                            All Services

                        </option>

                        @foreach($services as $service)

                            <option
                                value="{{ $service->id }}"
                                @selected(request('service') == $service->id)>

                                {{ $service->name }}

                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- Status --}}
                <div class="col-lg-2">

                    <label class="form-label fw-semibold">

                        Status

                    </label>

                    <select
                        name="status"
                        class="form-select">

                        <option value="">All Statuses</option>

                        @foreach([
                            'Incoming',
                            'Assigned',
                            'Ongoing',
                            'Completed',
                            'Archived'
                        ] as $status)

                            <option
                                value="{{ $status }}"
                                @selected(request('status') == $status)>

                                {{ $status }}

                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- Priority --}}
                <div class="col-lg-2">

                    <label class="form-label fw-semibold">

                        Priority

                    </label>

                    <select
                        name="priority"
                        class="form-select">

                        <option value="">All Priorities</option>

                        @foreach([
                            'Low',
                            'Normal',
                            'High',
                            'Urgent'
                        ] as $priority)

                            <option
                                value="{{ $priority }}"
                                @selected(request('priority') == $priority)>

                                {{ $priority }}

                            </option>

                        @endforeach

                    </select>

                </div>
{{-- Date Received From --}}
<div class="col-lg-2">

    <label class="form-label fw-semibold">
        Date From
    </label>

    <input
        type="date"
        name="date_from"
        class="form-control"
        value="{{ request('date_from') }}">

</div>

{{-- Date Received To --}}
<div class="col-lg-2">

    <label class="form-label fw-semibold">
        Date To
    </label>

    <input
        type="date"
        name="date_to"
        class="form-control"
        value="{{ request('date_to') }}">

</div>
            </div>

            <hr>

            <div class="d-flex justify-content-end gap-2">

                

                <div class="d-flex gap-2">

    <button
        type="submit"
        class="btn btn-primary">

        <i class="bi bi-search me-1"></i>

        Search

    </button>

    <a
        href="{{ url()->current() }}"
        class="btn btn-outline-secondary">

        <i class="bi bi-arrow-counterclockwise me-1"></i>

        Reset

    </a>

</div>

            </div>

        </form>

    </div>

</div>
    {{-- Statistics --}}
    <div class="row mb-4">

        <div class="col-lg-3 mb-3">

            <x-admin.stat-card
                title="Total Requests"
                :value="$requests->total()"
                icon="bi-inbox-fill"
                color="primary"/>

        </div>

        <div class="col-lg-3 mb-3">

            <x-admin.stat-card
                title="Incoming"
                :value="$requests->where('status','Incoming')->count()"
                icon="bi-hourglass-top"
                color="warning"/>

        </div>

        <div class="col-lg-3 mb-3">

            <x-admin.stat-card
                title="Completed"
                :value="$requests->where('status','Completed')->count()"
                icon="bi-check-circle-fill"
                color="success"/>

        </div>

        <div class="col-lg-3 mb-3">

            <x-admin.stat-card
                title="High Priority"
                :value="$requests->whereIn('priority',['High','Urgent'])->count()"
                icon="bi-exclamation-triangle-fill"
                color="danger"/>

        </div>

    </div>

    {{-- Search --}}
    <x-admin.search-bar
        placeholder="Search requests..." />

        <div class="d-flex justify-content-between align-items-center mb-3">

    <div class="text-muted small">

        Showing
        <strong>{{ $requests->firstItem() ?? 0 }}</strong>
        –
        <strong>{{ $requests->lastItem() ?? 0 }}</strong>

        of

        <strong>{{ $requests->total() }}</strong>

        requests

    </div>

</div>
    {{-- Table --}}
    <x-admin.table-card>

        <div class="table-responsive">

            <table
    id="requestsTable"
    class="table table-hover align-middle">

                <thead class="table-light">

                    <tr>

                        <th>Control No.</th>

                        <th>Date Received</th>

                        <th>Organization</th>

                        <th>Requestor</th>

                        <th>Service</th>

                        <th>Priority</th>

<th>Approval</th>

<th>Status</th>

                        <th>Due Date</th>

                        <th width="140" class="text-center">

                            Actions

                        </th>

                    </tr>

                </thead>

              <tbody>

@foreach($requests as $request)

    <tr>

        {{-- Control Number --}}
        <td>

            <strong>
                {{ $request->control_no }}
            </strong>

        </td>

        {{-- Date Received --}}
        <td>

            @if($request->date_received)

                {{ \Carbon\Carbon::parse($request->date_received)->format('M d, Y') }}

            @else

                <span class="text-muted">
                    -
                </span>

            @endif

        </td>

        {{-- Organization --}}
        <td>

            @if($request->organization)

                {{ $request->organization->name }}

            @else

                <span class="text-muted">
                    -
                </span>

            @endif

        </td>

        {{-- Requestor --}}
        <td>

            @if($request->contact)

                <div>
                    {{ $request->contact->full_name }}
                </div>

                @if(!empty($request->contact->email))

                    <small class="text-muted">
                        {{ $request->contact->email }}
                    </small>

                @endif

            @else

                <span class="text-muted">
                    -
                </span>

            @endif

        </td>

        {{-- Service --}}
        <td>

            @if($request->services->count())

                @foreach($request->services as $service)

                    <span class="badge bg-primary me-1 mb-1">

                        <i class="bi bi-tag-fill me-1"></i>

                        {{ $service->name }}

                    </span>

                @endforeach

            @else

                <span class="text-muted">
                    -
                </span>

            @endif

        </td>

        {{-- Priority --}}
        <td>

            @php

                $priorityClass = match($request->priority) {

                    'Low' => 'bg-success',

                    'Normal' => 'bg-primary',

                    'High' => 'bg-warning text-dark',

                    'Urgent' => 'bg-danger',

                    default => 'bg-secondary',

                };

            @endphp

            <span class="badge {{ $priorityClass }}">

                {{ $request->priority }}

            </span>

        </td>
{{-- Approval --}}
<td>

    @php

        $approvalClass = match($request->approval_status) {

            'Pending' => 'bg-warning text-dark',

            'Approved' => 'bg-success',

            'Declined' => 'bg-danger',

            'Cancelled' => 'bg-secondary',

            default => 'bg-secondary',

        };

        $approvalLabel = match($request->approval_status) {

            'Pending' => 'For Approval',

            'Approved' => 'Approved',

            'Declined' => 'Declined',

            'Cancelled' => 'Cancelled',

            default => $request->approval_status ?? '-',

        };

    @endphp

    <span class="badge {{ $approvalClass }}">

        {{ $approvalLabel }}

    </span>

</td>

        {{-- Status --}}
        <td>

            @php

                $statusClass = match($request->status) {

                    'Incoming' => 'bg-warning text-dark',

                    'Assigned' => 'bg-info',

                    'Ongoing' => 'bg-primary',

                    'Completed' => 'bg-success',

                    'Archived' => 'bg-secondary',

                    default => 'bg-dark',

                };

            @endphp

            <span class="badge {{ $statusClass }}">

                {{ $request->status }}

            </span>

        </td>

       {{-- Due Date --}}
<td>
    @if($request->due_date)

        @php
            $dueDate = \Carbon\Carbon::parse($request->due_date)->startOfDay();
            $today = \Carbon\Carbon::today();

            $daysUntilDue = $today->diffInDays($dueDate, false);
        @endphp

        @if($daysUntilDue < 0)

            <span class="badge bg-danger">
                <i class="bi bi-exclamation-circle me-1"></i>
                Overdue
            </span>

            <div class="small text-danger mt-1">
                {{ $dueDate->format('M d, Y') }}
            </div>

        @elseif($daysUntilDue === 0)

            <span class="badge bg-warning text-dark">
                <i class="bi bi-clock me-1"></i>
                Due Today
            </span>

            <div class="small text-muted mt-1">
                {{ $dueDate->format('M d, Y') }}
            </div>

        @elseif($daysUntilDue <= 3)

            <span class="badge bg-warning text-dark">
                <i class="bi bi-calendar-event me-1"></i>
                Due Soon
            </span>

            <div class="small text-muted mt-1">
                {{ $dueDate->format('M d, Y') }}
            </div>

        @else

            <div>
                {{ $dueDate->format('M d, Y') }}
            </div>

            <small class="text-muted">
                {{ $dueDate->diffForHumans() }}
            </small>

        @endif

    @else

        <span class="text-muted">-</span>

    @endif
</td>

        {{-- Actions --}}
        <td class="text-center">

            <a
                href="{{ route('requests.show', $request) }}"
                class="btn btn-sm btn-outline-primary"
                title="View">

                <i class="bi bi-eye"></i>

            </a>

            <a
                href="{{ route('requests.edit', $request) }}"
                class="btn btn-sm btn-outline-success"
                title="Edit">

                <i class="bi bi-pencil"></i>

            </a>

        </td>

    </tr>

@endforeach

</tbody>
            </table>

        </div>

    </x-admin.table-card>

    {{-- Pagination --}}
    <div class="mt-3">

        {{ $requests->links() }}

    </div>

</div>

@endsection