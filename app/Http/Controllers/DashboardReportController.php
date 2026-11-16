<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\DashboardReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardReportController extends Controller
{
      /**
 * Display the Reports Dashboard.
 */

public function index(Request $request)
{
    // Report date range
    $range = $request->query('range', 'all');

    $startDate = null;
    $endDate = null;

    switch ($range) {
        case 'month':
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
            break;

        case '3months':
            $startDate = Carbon::now()
                ->subMonths(2)
                ->startOfMonth();

            $endDate = Carbon::now()->endOfMonth();
            break;

        case 'year':
            $startDate = Carbon::now()->startOfYear();
            $endDate = Carbon::now()->endOfYear();
            break;

        case 'custom':
            if ($request->filled('start_date') && $request->filled('end_date')) {
                try {
                    $startDate = Carbon::parse($request->start_date)->startOfDay();
                    $endDate = Carbon::parse($request->end_date)->endOfDay();

                    if ($startDate->gt($endDate)) {
                        [$startDate, $endDate] = [$endDate, $startDate];
                    }
                } catch (\Exception $e) {
                    $range = 'all';
                    $startDate = null;
                    $endDate = null;
                }
            }
            break;

        default:
            $range = 'all';
            break;
    }

// Service filter for Monthly and Yearly Trends
$availableServices = DB::table('services')
    ->orderBy('name')
    ->get(['id', 'name']);

$selectedServiceId = $request->query('service_id');

if (
    !is_numeric($selectedServiceId) ||
    !$availableServices->contains('id', (int) $selectedServiceId)
) {
    $selectedServiceId = null;
} else {
    $selectedServiceId = (int) $selectedServiceId;
}


    // Base analytics query
    $analyticsRequests = DocumentRequest::query();

    if ($startDate && $endDate) {
        $analyticsRequests->whereBetween('date_received', [
            $startDate,
            $endDate,
        ]);
    }

    // Overview statistics
    $totalRequests = (clone $analyticsRequests)->count();

    $completedRequests = (clone $analyticsRequests)
        ->where('status', 'Completed')
        ->count();

    $ongoingRequests = (clone $analyticsRequests)
        ->whereIn('status', ['Assigned', 'Ongoing'])
        ->count();

    $overdueRequests = (clone $analyticsRequests)
        ->whereNotIn('status', ['Completed', 'Archived'])
        ->whereNotNull('due_date')
        ->whereDate('due_date', '<', today())
        ->count();

    // Request status chart
    $statusChart = (clone $analyticsRequests)
        ->select('status', DB::raw('COUNT(*) as total'))
        ->groupBy('status')
        ->pluck('total', 'status');

    // Priority chart
    $priorityChart = (clone $analyticsRequests)
        ->select('priority', DB::raw('COUNT(*) as total'))
        ->groupBy('priority')
        ->pluck('total', 'priority');

    // Request type chart
    $requestTypeChart = (clone $analyticsRequests)
        ->select('request_type', DB::raw('COUNT(*) as total'))
        ->groupBy('request_type')
        ->pluck('total', 'request_type');

    // Services chart
    $servicesQuery = DB::table('request_service')
        ->join('services', 'services.id', '=', 'request_service.service_id')
        ->join('requests', 'requests.id', '=', 'request_service.request_id')
        ->select('services.name', DB::raw('COUNT(request_service.id) as total'));

    if ($startDate && $endDate) {
        $servicesQuery->whereBetween('requests.date_received', [
            $startDate,
            $endDate,
        ]);
    }

    $servicesChart = $servicesQuery
        ->groupBy('services.id', 'services.name')
        ->orderByDesc('total')
        ->limit(10)
        ->pluck('total', 'name');

    // Organizations chart
    $organizationsQuery = DB::table('requests')
        ->join('organizations', 'organizations.id', '=', 'requests.organization_id')
        ->select('organizations.name', DB::raw('COUNT(requests.id) as total'))
        ->whereNotNull('requests.organization_id');

    if ($startDate && $endDate) {
        $organizationsQuery->whereBetween('requests.date_received', [
            $startDate,
            $endDate,
        ]);
    }

    $organizationsChart = $organizationsQuery
        ->groupBy('organizations.id', 'organizations.name')
        ->orderByDesc('total')
        ->limit(10)
        ->pluck('total', 'name');

// Monthly Service Trends
$monthlyServiceQuery = DB::table('request_service')
    ->join('services', 'services.id', '=', 'request_service.service_id')
    ->join('requests', 'requests.id', '=', 'request_service.request_id')
    ->whereNotNull('requests.date_received')
        ->when($selectedServiceId, function ($query) use ($selectedServiceId) {
        $query->where('request_service.service_id', $selectedServiceId);
    })
    ->select(
        'services.name as service_name',
        DB::raw("strftime('%Y-%m', requests.date_received) as period"),
        DB::raw('COUNT(request_service.id) as total')
    );

if ($startDate && $endDate) {
    $monthlyServiceQuery->whereBetween('requests.date_received', [
        $startDate,
        $endDate,
    ]);
}

$monthlyServiceRows = $monthlyServiceQuery
    ->groupBy(
        'services.id',
        'services.name',
        DB::raw("strftime('%Y-%m', requests.date_received)")
    )
    ->orderBy('period')
    ->get();

$trendServiceNames = $selectedServiceId
    ? $availableServices
        ->where('id', $selectedServiceId)
        ->pluck('name')
        ->values()
    : $servicesChart->keys()->take(5)->values();

$monthlyLabels = $monthlyServiceRows
    ->pluck('period')
    ->unique()
    ->values();

$monthlyServiceChart = $trendServiceNames->map(function ($serviceName) use (
    $monthlyServiceRows,
    $monthlyLabels
) {
    return [
        'label' => $serviceName,
        'data' => $monthlyLabels->map(function ($period) use (
            $monthlyServiceRows,
            $serviceName
        ) {
            return (int) ($monthlyServiceRows
                ->where('service_name', $serviceName)
                ->where('period', $period)
                ->sum('total'));
        })->values(),
    ];
})->values();

$monthlyLabels = $monthlyLabels->map(function ($period) {
    return Carbon::createFromFormat('Y-m', $period)->format('M Y');
});


// Yearly Service Trends
$yearlyServiceQuery = DB::table('request_service')
    ->join('services', 'services.id', '=', 'request_service.service_id')
    ->join('requests', 'requests.id', '=', 'request_service.request_id')
    ->whereNotNull('requests.date_received')
        ->when($selectedServiceId, function ($query) use ($selectedServiceId) {
        $query->where('request_service.service_id', $selectedServiceId);
    })
    ->select(
        'services.name as service_name',
        DB::raw("strftime('%Y', requests.date_received) as period"),
        DB::raw('COUNT(request_service.id) as total')
    );

if ($startDate && $endDate) {
    $yearlyServiceQuery->whereBetween('requests.date_received', [
        $startDate,
        $endDate,
    ]);
}

$yearlyServiceRows = $yearlyServiceQuery
    ->groupBy(
        'services.id',
        'services.name',
        DB::raw("strftime('%Y', requests.date_received)")
    )
    ->orderBy('period')
    ->get();

$yearlyLabels = $yearlyServiceRows
    ->pluck('period')
    ->unique()
    ->values();

$yearlyServiceChart = $trendServiceNames->map(function ($serviceName) use (
    $yearlyServiceRows,
    $yearlyLabels
) {
    return [
        'label' => $serviceName,
        'data' => $yearlyLabels->map(function ($period) use (
            $yearlyServiceRows,
            $serviceName
        ) {
            return (int) ($yearlyServiceRows
                ->where('service_name', $serviceName)
                ->where('period', $period)
                ->sum('total'));
        })->values(),
    ];
})->values();

$yearlyLabels = $yearlyLabels->values();


   
return view('dashboard.reports', compact(
    'range',
    'startDate',
    'endDate',
    'totalRequests',
    'completedRequests',
    'ongoingRequests',
    'overdueRequests',
    'statusChart',
    'priorityChart',
    'requestTypeChart',
    'servicesChart',
    'organizationsChart',
    'monthlyLabels',
    'monthlyServiceChart',
    'yearlyLabels',
    'yearlyServiceChart',
    'availableServices',
'selectedServiceId',
));

}

    /**
     * Generate Dashboard PDF Report
     */
    public function pdf()
    {
        ini_set('memory_limit', '512M');
        /*
        |--------------------------------------------------------------------------
        | Date Range
        |--------------------------------------------------------------------------
        */

        $range = request('range', 'all');

        $startDate = null;
        $endDate = null;

        switch ($range) {

            case 'month':

                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();

                break;

            case '3months':

                $startDate = Carbon::now()
                    ->subMonths(2)
                    ->startOfMonth();

                $endDate = Carbon::now()->endOfMonth();

                break;

            case 'year':

                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();

                break;

            case 'custom':

                $customStart = request('start_date');
                $customEnd = request('end_date');

                if ($customStart && $customEnd) {

                    try {

                        $startDate = Carbon::parse($customStart)
                            ->startOfDay();

                        $endDate = Carbon::parse($customEnd)
                            ->endOfDay();

                        if ($startDate->gt($endDate)) {

                            [$startDate, $endDate] = [
                                $endDate,
                                $startDate,
                            ];
                        }

                    } catch (\Exception $e) {

                        $range = 'all';

                        $startDate = null;
                        $endDate = null;
                    }
                }

                break;

            default:

                $range = 'all';

                break;
        
                }


      


        /*
        |--------------------------------------------------------------------------
        | Base Analytics Query
        |--------------------------------------------------------------------------
        */

        $analyticsRequests = DocumentRequest::query();

        if ($startDate && $endDate) {

            $analyticsRequests->whereBetween(
                'date_received',
                [
                    $startDate,
                    $endDate,
                ]
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Basic Statistics
        |--------------------------------------------------------------------------
        */

        $totalRequestCount = (clone $analyticsRequests)->count();

        $incomingCount = (clone $analyticsRequests)
            ->where('status', 'Incoming')
            ->count();

        $assignedCount = (clone $analyticsRequests)
            ->where('status', 'Assigned')
            ->count();

        $ongoingCount = (clone $analyticsRequests)
            ->where('status', 'Ongoing')
            ->count();

        $completedCount = (clone $analyticsRequests)
            ->where('status', 'Completed')
            ->count();

        $archivedCount = (clone $analyticsRequests)
            ->where('status', 'Archived')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Request Status
        |--------------------------------------------------------------------------
        */

        $statusChart = (clone $analyticsRequests)
            ->select(
                'status',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('status')
            ->pluck(
                'total',
                'status'
            )
            ->toArray();


        /*
        |--------------------------------------------------------------------------
        | Priority Distribution
        |--------------------------------------------------------------------------
        */

        $priorityChart = (clone $analyticsRequests)
            ->select(
                'priority',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('priority')
            ->pluck(
                'total',
                'priority'
            )
            ->toArray();


        /*
        |--------------------------------------------------------------------------
        | Request Type Distribution
        |--------------------------------------------------------------------------
        */

        $requestTypeChart = (clone $analyticsRequests)
            ->select(
                'request_type',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('request_type')
            ->pluck(
                'total',
                'request_type'
            )
            ->toArray();


        /*
        |--------------------------------------------------------------------------
        | Services Requested
        |--------------------------------------------------------------------------
        */

        $servicesQuery = DB::table('request_service')
            ->join(
                'services',
                'services.id',
                '=',
                'request_service.service_id'
            )
            ->join(
                'requests',
                'requests.id',
                '=',
                'request_service.request_id'
            )
            ->select(
                'services.name',
                DB::raw('COUNT(request_service.id) as total')
            );

        if ($startDate && $endDate) {

            $servicesQuery->whereBetween(
                'requests.date_received',
                [
                    $startDate,
                    $endDate,
                ]
            );
        }

        $servicesChart = $servicesQuery
            ->groupBy(
                'services.id',
                'services.name'
            )
            ->orderByDesc('total')
            ->limit(10)
            ->pluck(
                'total',
                'name'
            )
            ->toArray();


        /*
        |--------------------------------------------------------------------------
        | Organizations Requested
        |--------------------------------------------------------------------------
        */

        $organizationsQuery = DB::table('requests')
            ->join(
                'organizations',
                'organizations.id',
                '=',
                'requests.organization_id'
            )
            ->select(
                'organizations.name',
                DB::raw('COUNT(requests.id) as total')
            )
            ->whereNotNull('requests.organization_id');

        if ($startDate && $endDate) {

            $organizationsQuery->whereBetween(
                'requests.date_received',
                [
                    $startDate,
                    $endDate,
                ]
            );
        }

        $organizationsChart = $organizationsQuery
            ->groupBy(
                'organizations.id',
                'organizations.name'
            )
            ->orderByDesc('total')
            ->limit(10)
            ->pluck(
                'total',
                'name'
            )
            ->toArray();


        /*
        |--------------------------------------------------------------------------
        | Performance Summary
        |--------------------------------------------------------------------------
        */

        $activeRequestCount = (clone $analyticsRequests)
            ->whereIn(
                'status',
                [
                    'Incoming',
                    'Assigned',
                    'Ongoing',
                ]
            )
            ->count();

        $completedForPeriod = (clone $analyticsRequests)
            ->where('status', 'Completed')
            ->whereNotNull('completed_at')
            ->count();

        $overdueCount = (clone $analyticsRequests)
            ->whereNotIn(
                'status',
                [
                    'Completed',
                    'Archived',
                ]
            )
            ->whereNotNull('due_date')
            ->whereDate(
                'due_date',
                '<',
                today()
            )
            ->count();

        $completionRate = $totalRequestCount > 0
            ? round(
                ($completedForPeriod / $totalRequestCount) * 100,
                1
            )
            : 0;

        $overdueRate = $totalRequestCount > 0
            ? round(
                ($overdueCount / $totalRequestCount) * 100,
                1
            )
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Average Processing Time
        |--------------------------------------------------------------------------
        */

        $completedRequests = (clone $analyticsRequests)
            ->where('status', 'Completed')
            ->whereNotNull('date_received')
            ->whereNotNull('completed_at')
            ->get(
                [
                    'date_received',
                    'completed_at',
                ]
            );

        $averageProcessingDays = 0;

        if ($completedRequests->count() > 0) {

            $totalProcessingDays = $completedRequests->sum(
                function ($request) {

                    return Carbon::parse(
                        $request->date_received
                    )->diffInDays(
                        Carbon::parse(
                            $request->completed_at
                        )
                    );
                }
            );

            $averageProcessingDays = round(
                $totalProcessingDays /
                $completedRequests->count(),
                1
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Report
        |--------------------------------------------------------------------------
        */

        $reportDate = Carbon::now();

        return Pdf::loadView(
            'dashboard.report',
            compact(
                'range',
                'startDate',
                'endDate',
                'reportDate',
                'totalRequestCount',
                'incomingCount',
                'assignedCount',
                'ongoingCount',
                'completedCount',
                'archivedCount',
                'statusChart',
                'priorityChart',
                'requestTypeChart',
                'servicesChart',
                'organizationsChart',
                'completionRate',
                'overdueRate',
                'averageProcessingDays',
                'activeRequestCount',
                'overdueCount'
            )
        )
        ->setPaper('a4', 'portrait')
        ->stream(
            'paio-dashboard-report-' .
            $reportDate->format('Y-m-d-His') .
            '.pdf'
        );
    }
    /**
 * Generate Excel Report
 */
public function excel(Request $request)
{
    // Report date range
    $range = $request->query('range', 'all');

    $startDate = null;
    $endDate = null;

    switch ($range) {
        case 'month':
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
            break;

        case '3months':
            $startDate = Carbon::now()->subMonths(2)->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
            break;

        case 'year':
            $startDate = Carbon::now()->startOfYear();
            $endDate = Carbon::now()->endOfYear();
            break;

        case 'custom':
            if ($request->filled('start_date') && $request->filled('end_date')) {
                try {
                    $startDate = Carbon::parse($request->start_date)->startOfDay();
                    $endDate = Carbon::parse($request->end_date)->endOfDay();

                    if ($startDate->gt($endDate)) {
                        [$startDate, $endDate] = [$endDate, $startDate];
                    }
                } catch (\Exception $e) {
                    $range = 'all';
                    $startDate = null;
                    $endDate = null;
                }
            }
            break;

        default:
            $range = 'all';
            break;
    }

    // Validate selected service
    $availableServices = DB::table('services')
        ->pluck('id')
        ->map(fn ($id) => (int) $id);

    $selectedServiceId = $request->query('service_id');

    if (
        !is_numeric($selectedServiceId) ||
        !$availableServices->contains((int) $selectedServiceId)
    ) {
        $selectedServiceId = null;
    } else {
        $selectedServiceId = (int) $selectedServiceId;
    }

    return Excel::download(
        new DashboardReportExport(
            $startDate,
            $endDate,
            $selectedServiceId
        ),
        'paio-report-' . now()->format('Y-m-d-His') . '.xlsx'
    );
}
}