@extends('layouts.master')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
         DASHBOARD TITLE
    ========================================================== --}}

    <h2 class="mb-4">
        Dashboard
    </h2>

   

    {{-- =========================================================
         REQUEST OVERVIEW
    ========================================================== --}}

    <h5 class="mb-3">
        <i class="bi bi-bar-chart-line me-2"></i>
        Request Overview
    </h5>

    <div class="row g-4">

        {{-- Total Requests --}}
        <x-stat-card
            title="Total Requests"
            :value="$totalRequestCount"
            icon="bi-files"
            :href="route('requests.index')"
        />

        {{-- Incoming --}}
        <x-stat-card
            title="Incoming"
            :value="$incomingCount"
            icon="bi-inbox"
            :href="route('requests.incoming')"
        />

        {{-- Assigned --}}
        <x-stat-card
            title="Assigned"
            :value="$assignedCount"
            icon="bi-person-check"
            :href="url('/requests?status=Assigned')"
        />

        {{-- Ongoing --}}
        <x-stat-card
            title="Ongoing"
            :value="$ongoingCount"
            icon="bi-hourglass-split"
            :href="route('requests.ongoing')"
        />

        {{-- Completed --}}
        <x-stat-card
            title="Completed"
            :value="$completedCount"
            icon="bi-check-circle"
            :href="route('requests.completed')"
        />

        {{-- Archived --}}
        <x-stat-card
            title="Archived"
            :value="$archivedCount"
            icon="bi-archive"
            :href="route('requests.archive.index')"
        />

    </div>


    {{-- =========================================================
         MY WORK
    ========================================================== --}}

    <h5 class="mt-5 mb-3">
        <i class="bi bi-person-workspace me-2"></i>
        My Work
    </h5>

    <div class="row g-4">

        {{-- My Assigned --}}
        <x-stat-card
            title="Assigned to Me"
            :value="$myAssignedCount"
            icon="bi-person-check-fill"
            :href="url('/requests?assigned_to=' . auth()->id())"
        />

        {{-- My Ongoing --}}
        <x-stat-card
            title="My Ongoing"
            :value="$myOngoingCount"
            icon="bi-arrow-repeat"
            :href="url('/requests?assigned_to=' . auth()->id() . '&status=Ongoing')"
        />

        {{-- High / Urgent --}}
        <x-stat-card
            title="High / Urgent"
            :value="$highPriorityCount"
            icon="bi-exclamation-triangle"
            :href="url('/requests?high_priority=1')"
        />

        {{-- Unassigned --}}
        <x-stat-card
            title="Unassigned"
            :value="$unassignedCount"
            icon="bi-person-x"
            :href="url('/requests?unassigned=1')"
        />

    </div>


    {{-- =========================================================
         DUE DATE MONITORING
    ========================================================== --}}

    <h5 class="mt-5 mb-3">
        <i class="bi bi-calendar-event me-2"></i>
        Due Date Monitoring
    </h5>

    <div class="row g-4">

        {{-- Overdue --}}
        <x-stat-card
            title="Overdue"
            :value="$overdueCount"
            icon="bi-exclamation-circle"
            :href="url('/requests?overdue=1')"
        />

        {{-- Due Today --}}
        <x-stat-card
            title="Due Today"
            :value="$dueTodayCount"
            icon="bi-calendar-check"
            :href="url('/requests?due_today=1')"
        />

        {{-- Due Soon --}}
        <x-stat-card
            title="Due Soon"
            :value="$dueSoonCount"
            icon="bi-calendar-event"
            :href="url('/requests?due_soon=1')"
        />

    </div>


    {{-- =========================================================
         SYSTEM OVERVIEW
    ========================================================== --}}

    <h5 class="mt-5 mb-3">
        <i class="bi bi-speedometer2 me-2"></i>
        System Overview
    </h5>

    <div class="row g-4">

        {{-- Users --}}
        <x-stat-card
            title="Users"
            :value="$userCount"
            icon="bi-people"
            :href="url('/users')"
        />

        {{-- Organizations --}}
        <x-stat-card
            title="Organizations"
            :value="$organizationCount"
            icon="bi-building"
            :href="url('/organizations')"
        />

    </div>


    {{-- =========================================================
         ANALYTICS
    ========================================================== --}}

<div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-end gap-3 mt-5 mb-3">

    {{-- Analytics Heading --}}
    <div>

        <h5 class="mb-1">
            <i class="bi bi-bar-chart-line me-2"></i>
            Request Analytics
        </h5>

        <small class="text-muted">
            Analyze request trends and distribution.
        </small>

    </div>


    {{-- Analytics Controls --}}
    <div class="d-flex flex-column flex-md-row align-items-md-end gap-2">

        {{-- Date Range Filter --}}
        <form
            method="GET"
            action="{{ route('dashboard') }}"
            class="d-flex flex-column flex-md-row align-items-md-end gap-2"
        >

            <div>

                <label class="form-label small text-muted mb-1">
                    Date Range
                </label>

                <select
                    name="range"
                    id="dashboardRange"
                    class="form-select form-select-sm"
                >

                    <option value="all"
                        {{ $range === 'all' ? 'selected' : '' }}>
                        All Time
                    </option>

                    <option value="month"
                        {{ $range === 'month' ? 'selected' : '' }}>
                        This Month
                    </option>

                    <option value="3months"
                        {{ $range === '3months' ? 'selected' : '' }}>
                        Last 3 Months
                    </option>

                    <option value="year"
                        {{ $range === 'year' ? 'selected' : '' }}>
                        This Year
                    </option>

                    <option value="custom"
                        {{ $range === 'custom' ? 'selected' : '' }}>
                        Custom Range
                    </option>

                </select>

            </div>


            {{-- Custom Start Date --}}
            <div
                id="customStartDate"
                class="{{ $range === 'custom' ? '' : 'd-none' }}"
            >

                <label class="form-label small text-muted mb-1">
                    From
                </label>

                <input
                    type="date"
                    name="start_date"
                    value="{{ request('start_date') }}"
                    class="form-control form-control-sm"
                >

            </div>


            {{-- Custom End Date --}}
            <div
                id="customEndDate"
                class="{{ $range === 'custom' ? '' : 'd-none' }}"
            >

                <label class="form-label small text-muted mb-1">
                    To
                </label>

                <input
                    type="date"
                    name="end_date"
                    value="{{ request('end_date') }}"
                    class="form-control form-control-sm"
                >

            </div>


            {{-- Apply --}}
            <button
                type="submit"
                class="btn btn-primary btn-sm"
            >
                <i class="bi bi-funnel me-1"></i>
                Apply
            </button>


            {{-- Reset --}}
            <a
                href="{{ route('dashboard') }}"
                class="btn btn-outline-secondary btn-sm"
            >
                <i class="bi bi-arrow-counterclockwise me-1"></i>
                Reset
            </a>

        </form>


        {{-- PDF Report --}}
        <a
            href="{{ route('dashboard.report', request()->query()) }}"
            class="btn btn-outline-danger btn-sm"
            target="_blank"
        >
            <i class="bi bi-file-earmark-pdf me-1"></i>
            PDF Report
        </a>

    </div>

</div>


    {{-- =========================================================
         ROW 1 — STATUS & MONTHLY
    ========================================================== --}}

    <div class="row g-4">

        {{-- Request Status --}}
        <div class="col-lg-5">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 pt-4 px-4">

                    <h6 class="mb-1 fw-semibold">
                        Request Status
                    </h6>

                    <small class="text-muted">
                        Current distribution of requests
                    </small>

                </div>

                <div class="card-body">

                    <div style="height: 300px;">

                        <canvas
                            id="requestStatusChart"
                            data-chart='@json($statusChart)'
                        ></canvas>

                    </div>

                </div>

            </div>

        </div>


        {{-- Monthly Request Volume --}}
        <div class="col-lg-7">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 pt-4 px-4">

                    <h6 class="mb-1 fw-semibold">
    Request Volume
</h6>

<small class="text-muted">
    @if($range === 'month')
        Requests received during this month
    @elseif($range === '3months')
        Requests received over the last 3 months
    @elseif($range === 'year')
        Requests received during this year
    @elseif($range === 'custom' && $startDate && $endDate)
        Requests received from
        {{ $startDate->format('M d, Y') }}
        to
        {{ $endDate->format('M d, Y') }}
    @else
        Requests received over the last 12 months
    @endif
</small>

                </div>

                <div class="card-body">

                    <div style="height: 300px;">

                        <canvas
                            id="monthlyRequestChart"
                            data-labels='@json($monthlyRequestLabels)'
                            data-counts='@json($monthlyRequestCounts)'
                        ></canvas>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         ROW 2 — PRIORITY & REQUEST TYPE
    ========================================================== --}}

    <div class="row g-4 mt-1">

        {{-- Priority Distribution --}}
        <div class="col-lg-5">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 pt-4 px-4">

                    <h6 class="mb-1 fw-semibold">
                        Priority Distribution
                    </h6>

                    <small class="text-muted">
                        Requests grouped by priority
                    </small>

                </div>

                <div class="card-body">

                    <div style="height: 300px;">

                        <canvas
                            id="priorityChart"
                            data-chart='@json($priorityChart)'
                        ></canvas>

                    </div>

                </div>

            </div>

        </div>


        {{-- Request Type Distribution --}}
        <div class="col-lg-7">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 pt-4 px-4">

                    <h6 class="mb-1 fw-semibold">
                        Request Type Distribution
                    </h6>

                    <small class="text-muted">
                        Requests grouped by request type
                    </small>

                </div>

                <div class="card-body">

                    <div style="height: 300px;">

                        <canvas
                            id="requestTypeChart"
                            data-chart='@json($requestTypeChart)'
                        ></canvas>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         ROW 3 — SERVICES
    ========================================================== --}}

    <div class="row g-4 mt-1">

        <div class="col-12">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-0 pt-4 px-4">

                    <h6 class="mb-1 fw-semibold">
                        Services Requested
                    </h6>

                    <small class="text-muted">
                        Top services requested
                    </small>

                </div>

                <div class="card-body">

                    <div style="height: 320px;">

                        <canvas
                            id="servicesChart"
                            data-chart='@json($servicesChart)'
                        ></canvas>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- =========================================================
     ROW 4 — ORGANIZATIONS
========================================================== --}}

<div class="row g-4 mt-1">

    <div class="col-12">

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-0 pt-4 px-4">

                <h6 class="mb-1 fw-semibold">
                    Organizations Requested
                </h6>

                <small class="text-muted">
                    Top organizations submitting requests
                </small>

            </div>

            <div class="card-body">

                <div style="height: 320px;">

                    <canvas
                        id="organizationsChart"
                        data-chart='@json($organizationsChart)'
                    ></canvas>

                </div>

            </div>

        </div>

    </div>

</div>


    {{-- =========================================================
         PERFORMANCE SUMMARY
    ========================================================== --}}

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-2 mt-5 mb-3">

    <div>

        <h5 class="mb-1">
            <i class="bi bi-speedometer2 me-2"></i>
            Performance Summary
        </h5>

        <small class="text-muted">
            Performance metrics for the selected period.
        </small>

    </div>


    <div>

        @if($range === 'all')

            <span class="badge bg-light text-dark border">
    <i class="bi bi-calendar-range me-1"></i>
    All Time
</span>

        @elseif($range === 'month')

            <span class="badge bg-light text-dark border">
                <i class="bi bi-calendar-range me-1"></i>
                This Month
            </span>

        @elseif($range === '3months')

            <span class="badge bg-light text-dark border">
                <i class="bi bi-calendar-range me-1"></i>
                Last 3 Months
            </span>

        @elseif($range === 'year')

            <span class="badge bg-light text-dark border">
                <i class="bi bi-calendar-range me-1"></i>
                This Year
            </span>

        @elseif($range === 'custom' && $startDate && $endDate)

            <span class="badge bg-light text-dark border">
                <i class="bi bi-calendar-range me-1"></i>
                {{ $startDate->format('M d, Y') }}
                –
                {{ $endDate->format('M d, Y') }}
            </span>

        @endif

    </div>

</div>

    <div class="row g-4">

        {{-- Completion Rate --}}
        <div class="col-md-6 col-xl-3">

            <a
                href="{{ route('requests.completed') }}"
                class="dashboard-metric-link"
            >

                <div class="card border-0 shadow-sm h-100 dashboard-metric-card">

                    <div class="card-body">

                        <div class="d-flex align-items-center justify-content-between">

                            <div>

                                <div class="small text-muted mb-2">
                                    Completion Rate
                                </div>

                                <div class="fs-2 fw-bold text-dark">
                                    {{ number_format($completionRate, 1) }}%
                                </div>

                                <small class="text-muted">
                                    {{ $completedCount }}
                                    of
                                    {{ $totalRequestCount }}
                                    {{ $totalRequestCount === 1 ? 'request' : 'requests' }}
                                    completed
                                </small>

                            </div>

                            <div class="text-success fs-2">
                                <i class="bi bi-check-circle"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </a>

        </div>


        {{-- Overdue Rate --}}
        <div class="col-md-6 col-xl-3">

            <a
                href="{{ url('/requests?overdue=1') }}"
                class="dashboard-metric-link"
            >

                <div class="card border-0 shadow-sm h-100 dashboard-metric-card">

                    <div class="card-body">

                        <div class="d-flex align-items-center justify-content-between">

                            <div>

                                <div class="small text-muted mb-2">
                                    Overdue Rate
                                </div>

                                <div class="fs-2 fw-bold text-dark">
                                    {{ number_format($overdueRate, 1) }}%
                                </div>

                                <small class="text-muted">
                                    {{ $overdueCount }}
                                    overdue active
                                    {{ $overdueCount === 1 ? 'request' : 'requests' }}
                                </small>

                            </div>

                            <div class="text-danger fs-2">
                                <i class="bi bi-exclamation-circle"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </a>

        </div>


        {{-- Average Processing Time --}}
        <div class="col-md-6 col-xl-3">

            <a
                href="{{ route('requests.completed') }}"
                class="dashboard-metric-link"
            >

                <div class="card border-0 shadow-sm h-100 dashboard-metric-card">

                    <div class="card-body">

                        <div class="d-flex align-items-center justify-content-between">

                            <div>

                                <div class="small text-muted mb-2">
                                    Avg. Processing Time
                                </div>

                                <div class="fs-2 fw-bold text-dark">
                                    {{ number_format($averageProcessingDays, 1) }}
                                </div>

                                <small class="text-muted">
                                    {{ $averageProcessingDays === 1.0 ? 'day' : 'days' }}
                                    per completed request
                                </small>

                            </div>

                            <div class="text-primary fs-2">
                                <i class="bi bi-clock-history"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </a>

        </div>


        {{-- Active Requests --}}
        <div class="col-md-6 col-xl-3">

            <a
                href="{{ route('requests.index') }}"
                class="dashboard-metric-link"
            >

                <div class="card border-0 shadow-sm h-100 dashboard-metric-card">

                    <div class="card-body">

                        <div class="d-flex align-items-center justify-content-between">

                            <div>

                                <div class="small text-muted mb-2">
                                    Active Requests
                                </div>

                                <div class="fs-2 fw-bold text-dark">
                                    {{ $activeRequestCount }}
                                </div>

                                <small class="text-muted">
                                    Currently being processed
                                </small>

                            </div>

                            <div class="text-warning fs-2">
                                <i class="bi bi-hourglass-split"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </a>

        </div>

    </div>

</div>

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const rangeSelect = document.getElementById('range');

    const startDate = document.getElementById('customStartDate');

    const endDate = document.getElementById('customEndDate');


    function toggleCustomDates() {

        const isCustom =
            rangeSelect.value === 'custom';

        startDate.style.display =
            isCustom ? '' : 'none';

        endDate.style.display =
            isCustom ? '' : 'none';

    }


    rangeSelect.addEventListener(
        'change',
        toggleCustomDates
    );


    toggleCustomDates();

});

</script>

@endpush

@endsection