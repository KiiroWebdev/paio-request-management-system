
@extends('layouts.master')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-bar-chart-line me-2"></i>Reports
            </h2>
            <p class="text-muted mb-0">
                Generate and download PAIO request management reports.
            </p>
        </div>
    </div>



{{-- Report Period Filter --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <form action="{{ route('reports.index') }}" method="GET">
            <div class="row g-3 align-items-end">

                {{-- Reporting Period --}}
                <div class="col-md-3">
                    <label for="range" class="form-label fw-semibold">
                        Reporting Period
                    </label>

                    <select name="range" id="range" class="form-select">
                        <option value="all" {{ $range == 'all' ? 'selected' : '' }}>
                            All Time
                        </option>
                        <option value="month" {{ $range == 'month' ? 'selected' : '' }}>
                            This Month
                        </option>
                        <option value="3months" {{ $range == '3months' ? 'selected' : '' }}>
                            Last 3 Months
                        </option>
                        <option value="year" {{ $range == 'year' ? 'selected' : '' }}>
                            This Year
                        </option>
                        <option value="custom" {{ $range == 'custom' ? 'selected' : '' }}>
                            Custom Date Range
                        </option>
                    </select>
                </div>

                {{-- Service Filter --}}
                <div class="col-md-3">
                    <label for="service_id" class="form-label fw-semibold">
                        Select Service
                    </label>

                    <select name="service_id" id="service_id" class="form-select">
                        <option value="">All Services</option>

                        @foreach($availableServices as $service)
                            <option value="{{ $service->id }}"
                                {{ $selectedServiceId == $service->id ? 'selected' : '' }}>
                                {{ $service->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Custom Start Date --}}
                <div class="col-md-2" id="customStartDate"
                    style="{{ $range === 'custom' ? '' : 'display:none;' }}">
                    <label for="start_date" class="form-label fw-semibold">
                        Start Date
                    </label>

                    <input type="date"
                        name="start_date"
                        id="start_date"
                        class="form-control"
                        value="{{ $startDate ? $startDate->format('Y-m-d') : '' }}">
                </div>

                {{-- Custom End Date --}}
                <div class="col-md-2" id="customEndDate"
                    style="{{ $range === 'custom' ? '' : 'display:none;' }}">
                    <label for="end_date" class="form-label fw-semibold">
                        End Date
                    </label>

                    <input type="date"
                        name="end_date"
                        id="end_date"
                        class="form-control"
                        value="{{ $endDate ? $endDate->format('Y-m-d') : '' }}">
                </div>

                {{-- Apply Filter --}}
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i>
                        Apply Filter
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>


{{-- Reports Overview --}}
<div class="mb-4">

    <h5 class="fw-semibold mb-3">
        <i class="bi bi-speedometer2 me-2"></i>
        Reports Overview
    </h5>

    <div class="row g-3">

        {{-- Total Requests --}}
        <div class="col-xl-3 col-md-6">
            <a href="{{ route('requests.index') }}"
               class="text-decoration-none text-dark d-block h-100">

                <div class="card border-0 shadow-sm h-100 reports-stat-card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Requests</p>
                            <h3 class="fw-bold mb-0">
                                {{ number_format($totalRequests) }}
                            </h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                            <i class="bi bi-inbox fs-3"></i>
                        </div>
                    </div>
                </div>

            </a>
        </div>

        {{-- Completed Requests --}}
        <div class="col-xl-3 col-md-6">
            <a href="{{ route('requests.completed') }}"
               class="text-decoration-none text-dark d-block h-100">

                <div class="card border-0 shadow-sm h-100 reports-stat-card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Completed</p>
                            <h3 class="fw-bold text-success mb-0">
                                {{ number_format($completedRequests) }}
                            </h3>
                        </div>
                        <div class="bg-success bg-opacity-10 text-success rounded-3 p-3">
                            <i class="bi bi-check-circle fs-3"></i>
                        </div>
                    </div>
                </div>

            </a>
        </div>

        {{-- Ongoing Requests --}}
        <div class="col-xl-3 col-md-6">
            <a href="{{ route('requests.active-work') }}"
               class="text-decoration-none text-dark d-block h-100">

                <div class="card border-0 shadow-sm h-100 reports-stat-card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Ongoing</p>
                            <h3 class="fw-bold text-warning mb-0">
                                {{ number_format($ongoingRequests) }}
                            </h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3">
                            <i class="bi bi-hourglass-split fs-3"></i>
                        </div>
                    </div>
                </div>

            </a>
        </div>

        {{-- Overdue Requests --}}
        <div class="col-xl-3 col-md-6">
            <a href="{{ route('requests.overdue') }}"
               class="text-decoration-none text-dark d-block h-100">

                <div class="card border-0 shadow-sm h-100 reports-stat-card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Overdue</p>
                            <h3 class="fw-bold text-danger mb-0">
                                {{ number_format($overdueRequests) }}
                            </h3>
                        </div>
                        <div class="bg-danger bg-opacity-10 text-danger rounded-3 p-3">
                            <i class="bi bi-exclamation-triangle fs-3"></i>
                        </div>
                    </div>
                </div>

            </a>
        </div>

    </div>
</div>



{{-- Interactive Analytics Charts --}}
<div class="mb-4">

    <h5 class="fw-semibold mb-3">
        <i class="bi bi-graph-up-arrow me-2"></i>
        Request Analytics
    </h5>

    <div class="row g-3">

        {{-- Request Status --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h6 class="fw-semibold mb-3">Request Status Distribution</h6>
                    <div style="height: 300px;">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Priority --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h6 class="fw-semibold mb-3">Priority Distribution</h6>
                    <div style="height: 300px;">
                        <canvas id="priorityChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Request Type --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h6 class="fw-semibold mb-3">Request Type Distribution</h6>
                    <div style="height: 300px;">
                        <canvas id="requestTypeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Top Requested Services --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h6 class="fw-semibold mb-3">Top Requested Services</h6>
                    <div style="height: 300px;">
                        <canvas id="servicesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Organizations Served --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h6 class="fw-semibold mb-3">Organizations Served</h6>
                    <div style="height: 350px;">
                        <canvas id="organizationsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Monthly Service Trends --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h6 class="fw-semibold mb-3">
                        <i class="bi bi-calendar-month me-2"></i>
                        Monthly Service Trends
                    </h6>
                    <p class="text-muted small mb-3">
    @if($selectedServiceId)
        Showing monthly requests for the selected service.
    @else
        Comparing the top five requested services.
    @endif
</p>
                    <div style="height: 350px;">
                        <canvas id="monthlyServiceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Yearly Service Trends --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h6 class="fw-semibold mb-3">
                        <i class="bi bi-calendar-range me-2"></i>
                        Yearly Service Trends
                    </h6>
                    <p class="text-muted small mb-3">
    @if($selectedServiceId)
        Showing yearly requests for the selected service.
    @else
        Comparing the top five requested services.
    @endif
</p>
                    <div style="height: 350px;">
                        <canvas id="yearlyServiceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
{{-- Report Generation --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="fw-semibold mb-0">
            <i class="bi bi-file-earmark-pdf me-2 text-danger"></i>
            Generate Dashboard Report
        </h5>
    </div>

    <div class="card-body p-4">

        <p class="text-muted mb-4">
            Download a PDF report using the currently selected reporting period.
        </p>

        <form action="{{ route('dashboard.report') }}" method="GET"
              target="_blank">

              <a href="{{ route('dashboard.report', request()->query()) }}"
   target="_blank"
   class="btn btn-primary">
    <i class="bi bi-file-pdf"></i> Preview PDF Report
</a>

            <input type="hidden" name="range" value="{{ $range }}">

            @if($range === 'custom')
                <input type="hidden" name="start_date"
                       value="{{ $startDate ? $startDate->format('Y-m-d') : '' }}">

                <input type="hidden" name="end_date"
                       value="{{ $endDate ? $endDate->format('Y-m-d') : '' }}">
            @endif

            <button type="submit" class="btn btn-danger">
                <i class="bi bi-file-earmark-pdf me-2"></i>
                Generate PDF Report
            </button>

        </form>
 {{-- Excel Export --}}
<form action="{{ route('dashboard.report.excel') }}" method="GET"
      class="mt-3">

    <input type="hidden" name="range" value="{{ $range }}">

    @if($range === 'custom')
        <input type="hidden" name="start_date"
               value="{{ $startDate ? $startDate->format('Y-m-d') : '' }}">

        <input type="hidden" name="end_date"
               value="{{ $endDate ? $endDate->format('Y-m-d') : '' }}">
    @endif

    @if(!empty($selectedServiceId))
        <input type="hidden" name="service_id"
               value="{{ $selectedServiceId }}">
    @endif

    <button type="submit" class="btn btn-success">
        <i class="bi bi-file-earmark-excel me-2"></i>
        Export to Excel
    </button>

</form>
    </div>

   
</div>

    {{-- Report Contents --}}
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body p-4">

            <h5 class="fw-semibold mb-3">
                <i class="bi bi-list-check me-2"></i>
                Report Contents
            </h5>

            <div class="row g-3">

                <div class="col-md-4">
                    <i class="bi bi-clipboard-data text-success me-2"></i>
                    Request Statistics
                </div>

                <div class="col-md-4">
                    <i class="bi bi-bar-chart text-success me-2"></i>
                    Request Status Distribution
                </div>

                <div class="col-md-4">
                    <i class="bi bi-flag text-success me-2"></i>
                    Priority Distribution
                </div>

                <div class="col-md-4">
                    <i class="bi bi-file-earmark-text text-success me-2"></i>
                    Request Type Distribution
                </div>

                <div class="col-md-4">
                    <i class="bi bi-gear text-success me-2"></i>
                    Most Requested Services
                </div>

                <div class="col-md-4">
                    <i class="bi bi-building text-success me-2"></i>
                    Organizations Served




                </div>

                <div class="col-md-4">
                    <i class="bi bi-check-circle text-success me-2"></i>
                    Completion Rate
                </div>

                <div class="col-md-4">
                    <i class="bi bi-exclamation-triangle text-success me-2"></i>
                    Overdue Requests
                </div>

                <div class="col-md-4">
                    <i class="bi bi-clock text-success me-2"></i>
                    Average Processing Time
                </div>

            </div>

        </div>
    </div>

</div>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const rangeSelect = document.getElementById('range');
    const startField = document.getElementById('customStartDate');
    const endField = document.getElementById('customEndDate');
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');

    function toggleCustomDates() {
        const isCustom = rangeSelect.value === 'custom';

        startField.style.display = isCustom ? 'block' : 'none';
        endField.style.display = isCustom ? 'block' : 'none';

        startDate.required = isCustom;
        endDate.required = isCustom;
    }

    rangeSelect.addEventListener('change', toggleCustomDates);

    toggleCustomDates();

});

</script>
<style>
    .reports-stat-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .reports-stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.10) !important;
    }
</style>


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const chartData = {
        status: @json($statusChart),
        priority: @json($priorityChart),
        requestType: @json($requestTypeChart),
        services: @json($servicesChart),
        organizations: @json($organizationsChart)
    };

    const colors = [
        '#0d6efd',
        '#198754',
        '#ffc107',
        '#dc3545',
        '#6f42c1',
        '#20c997',
        '#fd7e14',
        '#6c757d',
        '#0dcaf0',
        '#d63384'
    ];

    function createChart(elementId, type, data, horizontal = false) {

        const canvas = document.getElementById(elementId);

        if (!canvas) return;

        const labels = Object.keys(data);
        const values = Object.values(data).map(Number);

        new Chart(canvas, {
            type: type,

            data: {
                labels: labels,
                datasets: [{
                    label: 'Number of Requests',
                    data: values,
                    backgroundColor: colors,
                    borderWidth: 1,
                    borderRadius: type === 'bar' ? 5 : 0
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                indexAxis: horizontal ? 'y' : 'x',

                plugins: {
                    legend: {
                        display: type === 'doughnut',
                        position: 'bottom'
                    },
                    tooltip: {
                        enabled: true
                    }
                },

                scales: type === 'bar' ? {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    },
                    y: {
                        beginAtZero: true
                    }
                } : {}
            }
        });
    }

    createChart('statusChart', 'doughnut', chartData.status);
    createChart('priorityChart', 'doughnut', chartData.priority);
    createChart('requestTypeChart', 'bar', chartData.requestType);
    createChart('servicesChart', 'bar', chartData.services, true);
    createChart('organizationsChart', 'bar', chartData.organizations, true);


// Monthly and Yearly Service Trends
function createServiceTrendChart(elementId, labels, datasets) {
    const canvas = document.getElementById(elementId);

    if (!canvas) return;

    const trendColors = [
        '#0d6efd',
        '#198754',
        '#ffc107',
        '#dc3545',
        '#6f42c1'
    ];

    new Chart(canvas, {
        type: 'line',

        data: {
            labels: labels,
            datasets: datasets.map((dataset, index) => ({
                label: dataset.label,
                data: dataset.data,
                borderColor: trendColors[index % trendColors.length],
                backgroundColor: trendColors[index % trendColors.length],
                borderWidth: 2,
                tension: 0.3,
                fill: false,
                pointRadius: 4,
                pointHoverRadius: 6
            }))
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            },

            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
}

createServiceTrendChart(
    'monthlyServiceChart',
    @json($monthlyLabels),
    @json($monthlyServiceChart)
);

createServiceTrendChart(
    'yearlyServiceChart',
    @json($yearlyLabels),
    @json($yearlyServiceChart)
);


});
</script>
@endpush

@endsection
