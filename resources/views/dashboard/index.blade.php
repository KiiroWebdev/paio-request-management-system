@extends('layouts.master')

@section('content')
<div class="container-fluid paio-dashboard">

    {{-- Welcome Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Welcome to PAIO</h2>
            <p class="text-muted mb-0">
                Public Affairs and Information Office Request Management System
            </p>
        </div>

        <a href="{{ route('request-wizard.services') }}"
           class="btn btn-success">
            <i class="bi bi-plus-circle me-2"></i>New Request
        </a>
    </div>

    {{-- Quick Overview --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0 fw-semibold">Request Overview</h5>

        <button type="button"
        class="btn btn-sm btn-outline-success"
        data-bs-toggle="modal"
        data-bs-target="#customizeStatsModal">
    <i class="bi bi-sliders me-1"></i>
    Customize Statistics
</button>
    </div>



{{-- Customizable Dashboard Statistics --}}
<div class="mb-4">
    <div class="row g-3">

        @if(in_array('incoming', $visibleStats))
            <x-stat-card
                title="Incoming"
                :value="$incomingCount"
                icon="bi-inbox"
                :href="route('requests.incoming')"
            />
        @endif

        @if(in_array('ongoing', $visibleStats))
            <x-stat-card
                title="Ongoing"
                :value="$ongoingCount"
                icon="bi-hourglass-split"
                :href="route('requests.ongoing')"
            />
        @endif

        @if(in_array('completed', $visibleStats))
            <x-stat-card
                title="Completed"
                :value="$completedCount"
                icon="bi-check-circle"
                :href="route('requests.completed')"
            />
        @endif

        @if(in_array('total', $visibleStats))
            <x-stat-card
                title="Total Requests"
                :value="$totalRequestCount"
                icon="bi-files"
                :href="route('requests.index')"
            />
        @endif

        @if(in_array('assigned', $visibleStats))
            <x-stat-card
                title="Assigned"
                :value="$assignedCount"
                icon="bi-person-check"
                :href="url('/requests?status=Assigned')"
            />
        @endif

        @if(in_array('archived', $visibleStats))
            <x-stat-card
                title="Archived"
                :value="$archivedCount"
                icon="bi-archive"
                :href="route('requests.archive.index')"
            />
        @endif

    </div>
</div>

{{-- Customize Statistics Modal --}}
<div class="modal fade"
     id="customizeStatsModal"
     tabindex="-1"
     aria-labelledby="customizeStatsModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form method="POST"
                  action="{{ route('dashboard.preferences.update') }}">

                @csrf

                <div class="modal-header">
                    <div>
                        <h5 class="modal-title fw-semibold"
                            id="customizeStatsModalLabel">
                            Customize Statistics
                        </h5>
                        <small class="text-muted">
                            Choose which cards appear on your dashboard.
                        </small>
                    </div>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    @php
                        $statOptions = [
                            'incoming' => 'Incoming',
                            'ongoing' => 'Ongoing',
                            'completed' => 'Completed',
                            'total' => 'Total Requests',
                            'assigned' => 'Assigned',
                            'archived' => 'Archived',
                        ];
                    @endphp

                    @foreach($statOptions as $key => $label)
                        <div class="form-check py-2">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="visible_stats[]"
                                value="{{ $key }}"
                                id="stat-{{ $key }}"
                                {{ in_array($key, old('visible_stats', $visibleStats)) ? 'checked' : '' }}
                            >

                            <label class="form-check-label"
                                   for="stat-{{ $key }}">
                                {{ $label }}
                            </label>
                        </div>
                    @endforeach

                    <div class="small text-muted mt-2">
                        Select at least one statistic.
                        Your preferences will be saved to your account.
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check2 me-1"></i>
                        Save Preferences
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>


    
{{-- Main Modules --}}
<div class="mb-3">
    <h5 class="fw-semibold mb-1">Quick Access</h5>
    <p class="text-muted small mb-0">
        Select a module to continue.
    </p>
</div>

<div class="row row-cols-2 row-cols-sm-3 row-cols-lg-4 row-cols-xl-5 g-3">

    {{-- Request Management --}}
    <div class="col">
        <a href="{{ route('requests.index') }}" class="paio-module-link">
            <div class="paio-module-card">
                <div class="paio-module-image">
                    <img src="{{ asset('images/quick-access/request-management.png') }}"
                         alt="Request Management">
                </div>
                <h6>Request Management</h6>
            </div>
        </a>
    </div>

    {{-- Kanban Board --}}
    <div class="col">
        <a href="{{ route('requests.kanban') }}" class="paio-module-link">
            <div class="paio-module-card">
                <div class="paio-module-image">
                    <img src="{{ asset('images/quick-access/kanban-board.png') }}"
                         alt="Kanban Board">
                </div>
                <h6>Kanban Board</h6>
            </div>
        </a>
    </div>

    {{-- Request Approvals --}}
    <div class="col">
        <a href="{{ route('request-approvals.index') }}" class="paio-module-link">
            <div class="paio-module-card">
                <div class="paio-module-image">
                    <img src="{{ asset('images/quick-access/request-approvals.png') }}"
                         alt="Request Approvals">
                </div>
                <h6>Request Approvals</h6>
            </div>
        </a>
    </div>

    {{-- Archives --}}
    <div class="col">
        <a href="{{ route('requests.archive.index') }}" class="paio-module-link">
            <div class="paio-module-card">
                <div class="paio-module-image">
                    <img src="{{ asset('images/quick-access/archives.png') }}"
                         alt="Archives">
                </div>
                <h6>Archives</h6>
            </div>
        </a>
    </div>

    {{-- Services --}}
    <div class="col">
        <a href="{{ route('services.index') }}" class="paio-module-link">
            <div class="paio-module-card">
                <div class="paio-module-image">
                    <img src="{{ asset('images/quick-access/services.png') }}"
                         alt="Services">
                </div>
                <h6>Services</h6>
            </div>
        </a>
    </div>

    {{-- Organizations --}}
    <div class="col">
        <a href="{{ route('organizations.index') }}" class="paio-module-link">
            <div class="paio-module-card">
                <div class="paio-module-image">
                    <img src="{{ asset('images/quick-access/organizations.png') }}"
                         alt="Organizations">
                </div>
                <h6>Organizations</h6>
            </div>
        </a>
    </div>

    {{-- Contacts --}}
    <div class="col">
        <a href="{{ route('contacts.index') }}" class="paio-module-link">
            <div class="paio-module-card">
                <div class="paio-module-image">
                    <img src="{{ asset('images/quick-access/contacts.png') }}"
                         alt="Contacts">
                </div>
                <h6>Contacts</h6>
            </div>
        </a>
    </div>

    {{-- User Management --}}
    @if(auth()->user()->can('admin'))
        <div class="col">
            <a href="{{ route('users.index') }}" class="paio-module-link">
                <div class="paio-module-card">
                    <div class="paio-module-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <h6>User Management</h6>
                </div>
            </a>
        </div>
    @endif

    {{-- Reports --}}
    <div class="col">
        <a href="{{ route('reports.index') }}" class="paio-module-link">
            <div class="paio-module-card">
                <div class="paio-module-image">
                    <img src="{{ asset('images/quick-access/reports.png') }}"
                         alt="Reports">
                </div>
                <h6>Reports</h6>
            </div>
        </a>
    </div>

</div>

</div>
@endsection