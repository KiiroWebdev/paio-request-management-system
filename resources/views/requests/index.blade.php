@extends('layouts.master')

@section('page-title', 'Incoming Requests')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <x-admin.page-header
        title="Incoming Requests"
        subtitle="Manage all document requests">

        <a href="{{ route('request-wizard.services') }}"
           class="btn btn-primary">

            <i class="bi bi-plus-circle"></i>

            New Request

        </a>

    </x-admin.page-header>

    <x-admin.breadcrumb
        :items="['Request Management', 'Incoming Requests']"/>

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
                            placeholder="Control No. or Subject..."
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

            </div>

            <hr>

            <div class="d-flex justify-content-end gap-2">

                <a
                    href="{{ route('requests.index') }}"
                    class="btn btn-outline-secondary">

                    <i class="bi bi-arrow-clockwise"></i>

                    Reset

                </a>

                <button
                    type="submit"
                    class="btn btn-primary">

                    <i class="bi bi-search"></i>

                    Search

                </button>

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

                        <th>Status</th>

                        <th>Due Date</th>

                        <th width="140" class="text-center">

                            Actions

                        </th>

                    </tr>

                </thead>

                <tbody>

                @forelse($requests as $request)

                    <tr>

                        <td>

    <div class="fw-bold">

        {{ $request->control_no }}

    </div>

    <small class="text-muted">

        #{{ $request->id }}

    </small>

</td>

                        <td>

                            {{ optional($request->date_received)->format('M d, Y') }}

                        </td>

                        <td>

    @if($request->organization)

        <div class="fw-semibold">

            {{ $request->organization->name }}

        </div>

    @else

        <span class="text-muted">

            Walk-in / Internal

        </span>

    @endif

</td>

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

                        <td>

    @forelse($request->services as $service)

        <span class="badge bg-primary me-1 mb-1">

            <i class="bi bi-tag-fill me-1"></i>

            {{ $service->name }}

        </span>

    @empty

        <span class="text-muted">

            -

        </span>

    @endforelse

</td>

                        {{-- Priority --}}
                        <td>

                            @php

                                $priorityClass = match($request->priority){

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

                        {{-- Status --}}
                        <td>

                            @php

                                $statusClass = match($request->status){

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

        <div>

            {{ $request->due_date->format('M d, Y') }}

        </div>

        <small class="text-muted">

            {{ $request->due_date->diffForHumans() }}

        </small>

    @else

        <span class="text-muted">

            -

        </span>

    @endif

</td>

<td class="text-center">

    <a href="{{ route('requests.show', $request) }}"
       class="btn btn-sm btn-outline-primary"
       title="View">

        <i class="bi bi-eye"></i>

    </a>

    <a href="{{ route('requests.edit', $request) }}"
       class="btn btn-sm btn-outline-success"
       title="Edit">

        <i class="bi bi-pencil"></i>

    </a>

</td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="9">

                            <x-admin.empty-state
                                colspan="9"
                                icon="bi-inbox"
                                title="No Requests Found"
                                message="Click the button below to create the first request."
                                buttonText="New Request"
                                :buttonRoute="route('request-wizard.services')" />

                        </td>

                    </tr>

                @endforelse

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