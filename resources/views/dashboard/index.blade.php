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

    <div class="row g-3">

        <div class="col-12 col-sm-6 col-xl-4">
            <a href="{{ route('requests.index') }}"
               class="paio-module-link">
                <div class="paio-module-card">
                    <div class="paio-module-icon">
                        <i class="bi bi-folder2-open"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6>Request Management</h6>
                        <p>View and manage document requests.</p>
                    </div>
                    <i class="bi bi-chevron-right paio-module-arrow"></i>
                </div>
            </a>
        </div>

        <div class="col-12 col-sm-6 col-xl-4">
            <a href="{{ route('request-approvals.index') }}"
               class="paio-module-link">
                <div class="paio-module-card">
                    <div class="paio-module-icon">
                        <i class="bi bi-clipboard-check"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6>Request Approvals</h6>
                        <p>Review requests requiring approval.</p>
                    </div>
                    <i class="bi bi-chevron-right paio-module-arrow"></i>
                </div>
            </a>
        </div>

        <div class="col-12 col-sm-6 col-xl-4">
            <a href="{{ route('requests.archive.index') }}"
               class="paio-module-link">
                <div class="paio-module-card">
                    <div class="paio-module-icon">
                        <i class="bi bi-archive"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6>Archives</h6>
                        <p>Access archived requests and records.</p>
                    </div>
                    <i class="bi bi-chevron-right paio-module-arrow"></i>
                </div>
            </a>
        </div>

        <div class="col-12 col-sm-6 col-xl-4">
            <a href="{{ route('services.index') }}"
               class="paio-module-link">
                <div class="paio-module-card">
                    <div class="paio-module-icon">
                        <i class="bi bi-grid"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6>Services</h6>
                        <p>View available services and configurations.</p>
                    </div>
                    <i class="bi bi-chevron-right paio-module-arrow"></i>
                </div>
            </a>
        </div>

        <div class="col-12 col-sm-6 col-xl-4">
            <a href="{{ route('organizations.index') }}"
               class="paio-module-link">
                <div class="paio-module-card">
                    <div class="paio-module-icon">
                        <i class="bi bi-building"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6>Organizations</h6>
                        <p>Manage organizations submitting requests.</p>
                    </div>
                    <i class="bi bi-chevron-right paio-module-arrow"></i>
                </div>
            </a>
        </div>

        <div class="col-12 col-sm-6 col-xl-4">
            <a href="{{ route('contacts.index') }}"
               class="paio-module-link">
                <div class="paio-module-card">
                    <div class="paio-module-icon">
                        <i class="bi bi-person-lines-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6>Contacts</h6>
                        <p>Access contact information.</p>
                    </div>
                    <i class="bi bi-chevron-right paio-module-arrow"></i>
                </div>
            </a>
        </div>

        @if(auth()->user()->can('admin'))
            <div class="col-12 col-sm-6 col-xl-4">
                <a href="{{ route('users.index') }}"
                   class="paio-module-link">
                    <div class="paio-module-card">
                        <div class="paio-module-icon">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6>User Management</h6>
                            <p>Manage system users and access.</p>
                        </div>
                        <i class="bi bi-chevron-right paio-module-arrow"></i>
                    </div>
                </a>
            </div>
        @endif

        <div class="col-12 col-sm-6 col-xl-4">
            <a href="{{ route('dashboard.report') }}"
               class="paio-module-link"
               target="_blank"
               rel="noopener">
                <div class="paio-module-card">
                    <div class="paio-module-icon">
                        <i class="bi bi-file-earmark-pdf"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6>Reports</h6>
                        <p>Generate the current PDF dashboard report.</p>
                    </div>
                    <i class="bi bi-chevron-right paio-module-arrow"></i>
                </div>
            </a>
        </div>

    </div>
</div>
@endsection