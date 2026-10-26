<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <title>PAIO Dashboard Report</title>

<style>
    @page {
        margin: 35px 35px 45px 35px;
    }

    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 10px;
        color: #333;
    }

    /* ========================================
   HEADER
======================================== */

.header {
    width: 100%;
    margin-bottom: 15px;
}

.header-table {
    width: 75%;
    margin: 0 auto;
    border-collapse: collapse;
    table-layout: fixed;
}

.header-table td {
    border: none !important;
    padding: 0 !important;
    vertical-align: middle;
}

.logo-cell {
    width: 15%;
}

.header-center {
    width: 70%;
    text-align: center;
}

.left-logo {
    text-align: right;
    padding-right: 8px !important;
}

.right-logo {
    text-align: left;
    padding-left: 8px !important;
}

.city-logo {
    width: 55px;
    height: 55px;
}

.paio-logo {
    width: 60px;
    height: 60px;
}

.header h1 {
    margin: 0;
    font-size: 16px;
    font-weight: bold;
    white-space: nowrap;
}

.header h2 {
    margin: 3px 0 0;
    font-size: 10px;
    font-weight: normal;
    color: #555;
}

.header .report-title {
    margin-top: 8px;
    font-size: 13px;
    font-weight: bold;
}

    /* ========================================
       REPORT PERIOD
    ======================================== */

    .period {
        text-align: center;
        margin-bottom: 20px;
        color: #666;
    }


    /* ========================================
       SECTIONS
    ======================================== */

    .section {
        margin-top: 18px;
        margin-bottom: 8px;
    }

    .section-title {
        font-size: 12px;
        font-weight: bold;
        border-bottom: 1px solid #999;
        padding-bottom: 5px;
        margin-bottom: 8px;
    }


    /* ========================================
       TABLES
    ======================================== */

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        background: #f1f1f1;
        font-weight: bold;
    }

    th,
    td {
        border: 1px solid #d5d5d5;
        padding: 6px 7px;
        text-align: left;
    }

    .text-right {
        text-align: right;
    }


    /* ========================================
       SUMMARY
    ======================================== */

    .summary-table td {
        width: 25%;
        text-align: center;
    }

    .summary-label {
        font-size: 9px;
        color: #666;
    }

    .summary-value {
        font-size: 16px;
        font-weight: bold;
        margin-top: 3px;
    }


    /* ========================================
       TWO COLUMN LAYOUT
    ======================================== */

    .two-column {
        width: 100%;
    }

    .two-column td {
        width: 50%;
        vertical-align: top;
        border: none;
        padding: 0 5px 0 0;
    }

    .two-column td:last-child {
        padding: 0 0 0 5px;
    }


    /* ========================================
       METRIC BOX
    ======================================== */

    .metric-box {
        border: 1px solid #d5d5d5;
        padding: 10px;
        margin-bottom: 8px;
    }

    .metric-label {
        color: #666;
        font-size: 9px;
    }

    .metric-value {
        font-size: 14px;
        font-weight: bold;
        margin-top: 4px;
    }


    /* ========================================
       BADGES
    ======================================== */

    .badge {
        display: inline-block;
        padding: 3px 6px;
        border-radius: 3px;
        font-size: 8px;
    }

    .badge-pending {
        background: #fff3cd;
    }

    .badge-approved {
        background: #d1e7dd;
    }

    .badge-declined {
        background: #f8d7da;
    }

    .badge-cancelled {
        background: #e2e3e5;
    }


    /* ========================================
       FOOTER
    ======================================== */

    .footer {
        position: fixed;
        bottom: -25px;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 8px;
        color: #777;
    }

    .page-number:after {
        content: counter(page);
    }
</style>
</head>

<body>

    {{-- =====================================================
         HEADER
    ====================================================== --}}
<div class="header">

    <table class="header-table">

        <tr>

            {{-- City Logo --}}
            <td class="logo-cell left-logo">

                <img
                    src="{{ public_path('images/city-logo.png') }}"
                    class="city-logo"
                >

            </td>


            {{-- Organization Name --}}
            <td class="header-center">

                <h1>
                    CITY GOVERNMENT OF SAN PEDRO
                </h1>

                <h2>
                    Public Affairs & Information Office
                </h2>

                <div class="report-title">
                    PAIO DASHBOARD REPORT
                </div>

            </td>


            {{-- PAIO Logo --}}
            <td class="logo-cell right-logo">

              <img
    src="{{ public_path('images/paio-logo.png') }}"
    width="60"
    height="60"
    class="paio-logo"
>

            </td>

        </tr>

    </table>

</div>


    {{-- =====================================================
         REPORT PERIOD
    ====================================================== --}}

    <div class="period">

        @if($range === 'all')

            Reporting Period:
            <strong>All Time</strong>

        @elseif($range === 'month')

            Reporting Period:
            <strong>This Month</strong>

        @elseif($range === '3months')

            Reporting Period:
            <strong>Last 3 Months</strong>

        @elseif($range === 'year')

            Reporting Period:
            <strong>This Year</strong>

        @elseif($range === 'custom' && $startDate && $endDate)

            Reporting Period:
            <strong>
                {{ $startDate->format('M d, Y') }}
                –
                {{ $endDate->format('M d, Y') }}
            </strong>

        @endif

        <br>

        Generated:
        <strong>
            {{ $reportDate->format('M d, Y h:i A') }}
        </strong>

    </div>


    {{-- =====================================================
         SUMMARY
    ====================================================== --}}

    <div class="section">

        <div class="section-title">
            Request Summary
        </div>

        <table class="summary-table">

            <tr>

                <td>
                    <div class="summary-label">
                        Total Requests
                    </div>

                    <div class="summary-value">
                        {{ number_format($totalRequestCount) }}
                    </div>
                </td>

                <td>
                    <div class="summary-label">
                        Incoming
                    </div>

                    <div class="summary-value">
                        {{ number_format($incomingCount) }}
                    </div>
                </td>

                <td>
                    <div class="summary-label">
                        Ongoing
                    </div>

                    <div class="summary-value">
                        {{ number_format($ongoingCount) }}
                    </div>
                </td>

                <td>
                    <div class="summary-label">
                        Completed
                    </div>

                    <div class="summary-value">
                        {{ number_format($completedCount) }}
                    </div>
                </td>

            </tr>

        </table>

    </div>


    {{-- =====================================================
         PERFORMANCE SUMMARY
    ====================================================== --}}

    <div class="section">

        <div class="section-title">
            Performance Summary
        </div>

        <table>

            <thead>
                <tr>
                    <th>Metric</th>
                    <th class="text-right">Value</th>
                </tr>
            </thead>

            <tbody>

                <tr>
                    <td>Completion Rate</td>
                    <td class="text-right">
                        {{ number_format($completionRate, 1) }}%
                    </td>
                </tr>

                <tr>
                    <td>Overdue Rate</td>
                    <td class="text-right">
                        {{ number_format($overdueRate, 1) }}%
                    </td>
                </tr>

                <tr>
                    <td>Average Processing Time</td>
                    <td class="text-right">
                        {{ number_format($averageProcessingDays, 1) }}
                        {{ $averageProcessingDays == 1 ? 'day' : 'days' }}
                    </td>
                </tr>

                <tr>
                    <td>Active Requests</td>
                    <td class="text-right">
                        {{ number_format($activeRequestCount) }}
                    </td>
                </tr>

                <tr>
                    <td>Overdue Requests</td>
                    <td class="text-right">
                        {{ number_format($overdueCount) }}
                    </td>
                </tr>

            </tbody>

        </table>

    </div>


    {{-- =====================================================
         REQUEST STATUS
    ====================================================== --}}

    <div class="section">

        <div class="section-title">
            Request Status
        </div>

        <table>

            <thead>
                <tr>
                    <th>Status</th>
                    <th class="text-right">Requests</th>
                </tr>
            </thead>

            <tbody>

                @forelse($statusChart as $status => $total)

                    <tr>

                        <td>
                            {{ $status }}
                        </td>

                        <td class="text-right">
                            {{ number_format($total) }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="2">
                            No request data available.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- =====================================================
         PRIORITY
    ====================================================== --}}

    <div class="section">

        <div class="section-title">
            Priority Distribution
        </div>

        <table>

            <thead>
                <tr>
                    <th>Priority</th>
                    <th class="text-right">Requests</th>
                </tr>
            </thead>

            <tbody>

                @forelse($priorityChart as $priority => $total)

                    <tr>

                        <td>
                            {{ $priority ?: 'Not Set' }}
                        </td>

                        <td class="text-right">
                            {{ number_format($total) }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="2">
                            No priority data available.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- =====================================================
         REQUEST TYPE
    ====================================================== --}}

    <div class="section">

        <div class="section-title">
            Request Type Distribution
        </div>

        <table>

            <thead>
                <tr>
                    <th>Request Type</th>
                    <th class="text-right">Requests</th>
                </tr>
            </thead>

            <tbody>

                @forelse($requestTypeChart as $type => $total)

                    <tr>

                        <td>
                            {{ $type ?: 'Not Set' }}
                        </td>

                        <td class="text-right">
                            {{ number_format($total) }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="2">
                            No request type data available.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- =====================================================
         SERVICES
    ====================================================== --}}

    <div class="section">

        <div class="section-title">
            Services Requested
        </div>

        <table>

            <thead>
                <tr>
                    <th>Service</th>
                    <th class="text-right">Requests</th>
                </tr>
            </thead>

            <tbody>

                @forelse($servicesChart as $service => $total)

                    <tr>

                        <td>
                            {{ $service }}
                        </td>

                        <td class="text-right">
                            {{ number_format($total) }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="2">
                            No service data available.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- =====================================================
         ORGANIZATIONS
    ====================================================== --}}

    <div class="section">

        <div class="section-title">
            Organizations Requested
        </div>

        <table>

            <thead>
                <tr>
                    <th>Organization</th>
                    <th class="text-right">Requests</th>
                </tr>
            </thead>

            <tbody>

                @forelse($organizationsChart as $organization => $total)

                    <tr>

                        <td>
                            {{ $organization }}
                        </td>

                        <td class="text-right">
                            {{ number_format($total) }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="2">
                            No organization data available.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- =====================================================
         FOOTER
    ====================================================== --}}

    <div class="footer">

        PAIO Request Management System
        &nbsp; | &nbsp;
        Generated {{ $reportDate->format('M d, Y h:i A') }}
        &nbsp; | &nbsp;
        Page <span class="page-number"></span>

    </div>

</body>
</html>