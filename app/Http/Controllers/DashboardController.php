<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequest;
use App\Models\Organization;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {

    /*
|--------------------------------------------------------------------------
| Analytics Date Range
|--------------------------------------------------------------------------
*/

$range = request('range', 'all');

$startDate = null;
$endDate = null;

switch ($range) {

    case 'month':

        $startDate = Carbon::now()
            ->startOfMonth();

        $endDate = Carbon::now()
            ->endOfMonth();

        break;


    case '3months':

        $startDate = Carbon::now()
            ->subMonths(2)
            ->startOfMonth();

        $endDate = Carbon::now()
            ->endOfMonth();

        break;


    case 'year':

        $startDate = Carbon::now()
            ->startOfYear();

        $endDate = Carbon::now()
            ->endOfYear();

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
| Analytics Request Query
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
        | Basic Request Counts
        |--------------------------------------------------------------------------
        */

        $totalRequestCount = DocumentRequest::count();

        $incomingCount = DocumentRequest::where(
            'status',
            'Incoming'
        )->count();

        $assignedCount = DocumentRequest::where(
            'status',
            'Assigned'
        )->count();

        $ongoingCount = DocumentRequest::where(
            'status',
            'Ongoing'
        )->count();

        $completedCount = DocumentRequest::where(
            'status',
            'Completed'
        )->count();

        $archivedCount = DocumentRequest::where(
            'status',
            'Archived'
        )->count();

        /*
|--------------------------------------------------------------------------
| Request Status Chart
|--------------------------------------------------------------------------
*/

$statusChart = [
    'Incoming' => (clone $analyticsRequests)
        ->where('status', 'Incoming')
        ->count(),

    'Assigned' => (clone $analyticsRequests)
        ->where('status', 'Assigned')
        ->count(),

    'Ongoing' => (clone $analyticsRequests)
        ->where('status', 'Ongoing')
        ->count(),

    'Completed' => (clone $analyticsRequests)
        ->where('status', 'Completed')
        ->count(),

    'Archived' => (clone $analyticsRequests)
        ->where('status', 'Archived')
        ->count(),
];

/*
|--------------------------------------------------------------------------
| Priority Chart
|--------------------------------------------------------------------------
*/

$priorityChart = [
    'Urgent' => (clone $analyticsRequests)
        ->where('priority', 'Urgent')
        ->count(),

    'High' => (clone $analyticsRequests)
        ->where('priority', 'High')
        ->count(),

    'Normal' => (clone $analyticsRequests)
        ->where('priority', 'Normal')
        ->count(),

    'Low' => (clone $analyticsRequests)
        ->where('priority', 'Low')
        ->count(),
];

/*
|--------------------------------------------------------------------------
| Request Type Chart
|--------------------------------------------------------------------------
*/

$requestTypeChart = (clone $analyticsRequests)
    ->select('request_type')
    ->selectRaw('COUNT(*) as total')
    ->whereNotNull('request_type')
    ->where('request_type', '!=', '')
    ->groupBy('request_type')
    ->orderByDesc('total')
    ->pluck('total', 'request_type')
    ->toArray();


/*
|--------------------------------------------------------------------------
| Services Requested Chart
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
        DB::raw(
            'COUNT(DISTINCT request_service.request_id) as total'
        )
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
| Organizations Requested Chart
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
        DB::raw(
            'COUNT(requests.id) as total'
        )
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

// Active Requests
$activeRequestCount = (clone $analyticsRequests)
    ->whereNotIn(
        'status',
        ['Completed', 'Archived']
    )
    ->count();


// Completed Requests
$analyticsTotalRequestCount = (clone $analyticsRequests)->count();

$completedRequestCount = (clone $analyticsRequests)
    ->where('status', 'Completed')
    ->count();

// Completion Rate for the selected analytics period
$completionRate = $analyticsTotalRequestCount > 0
    ? round(
        ($completedRequestCount / $analyticsTotalRequestCount) * 100,
        1
    )
    : 0;


// Overdue Active Requests
$overdueCount = (clone $analyticsRequests)
    ->whereDate(
        'due_date',
        '<',
        today()
    )
    ->whereNotIn(
        'status',
        ['Completed', 'Archived']
    )
    ->count();


// Overdue Rate
$overdueRate = $activeRequestCount > 0
    ? round(
        ($overdueCount / $activeRequestCount) * 100,
        1
    )
    : 0;


// Average Processing Time
$completedRequests = (clone $analyticsRequests)
    ->where('status', 'Completed')
    ->whereNotNull('date_received')
    ->whereNotNull('completed_at')
    ->get([
        'date_received',
        'completed_at',
    ]);

$averageProcessingDays = 0;

if ($completedRequests->count() > 0) {

    $totalProcessingDays = $completedRequests->sum(
        function ($request) {

            $receivedDate = Carbon::parse(
                $request->date_received
            );

            $completedDate = Carbon::parse(
                $request->completed_at
            );

            return max(
                0,
                $receivedDate->diffInDays($completedDate)
            );
        }
    );

    $averageProcessingDays = round(
        $totalProcessingDays / $completedRequests->count(),
        1
    );
}
/*
|--------------------------------------------------------------------------
| Request Volume Chart
|--------------------------------------------------------------------------
*/

$monthlyRequestLabels = [];
$monthlyRequestCounts = [];


/*
|--------------------------------------------------------------------------
| Determine Chart Period
|--------------------------------------------------------------------------
*/

$chartStartDate = $startDate;
$chartEndDate = $endDate;


/*
|--------------------------------------------------------------------------
| All Time
|--------------------------------------------------------------------------
|
| For All Time, display the most recent 12 months.
|
*/

if ($range === 'all') {

    $chartStartDate = Carbon::now()
        ->subMonths(11)
        ->startOfMonth();

    $chartEndDate = Carbon::now()
        ->endOfMonth();
}


/*
|--------------------------------------------------------------------------
| This Month
|--------------------------------------------------------------------------
|
| Display requests per day.
|
*/

if ($range === 'month') {

    $monthStart = Carbon::now()
        ->startOfMonth();

    $monthEnd = Carbon::now()
        ->endOfMonth();

    for (
        $date = $monthStart->copy();
        $date->lte($monthEnd);
        $date->addDay()
    ) {

        $monthlyRequestLabels[] =
            $date->format('M d');

        $monthlyRequestCounts[] =
            DocumentRequest::whereDate(
                'date_received',
                $date
            )->count();
    }
}


/*
|--------------------------------------------------------------------------
| Last 3 Months / This Year / Longer Custom Range
|--------------------------------------------------------------------------
|
| Display requests per month.
|
*/

elseif (
    $range === '3months' ||
    $range === 'year' ||
    (
        $range === 'custom' &&
        $chartStartDate &&
        $chartEndDate &&
        $chartStartDate->diffInDays($chartEndDate) > 31
    ) ||
    $range === 'all'
) {

    $currentMonth =
        $chartStartDate->copy()->startOfMonth();

    $lastMonth =
        $chartEndDate->copy()->startOfMonth();


    while (
        $currentMonth->lte($lastMonth)
    ) {

        $monthStart =
            $currentMonth->copy()->startOfMonth();

        $monthEnd =
            $currentMonth->copy()->endOfMonth();


        /*
        |--------------------------------------------------------------------------
        | Respect Selected Date Range
        |--------------------------------------------------------------------------
        */

        if (
            $chartStartDate &&
            $monthStart->lt(
                $chartStartDate->copy()->startOfDay()
            )
        ) {

            $monthStart =
                $chartStartDate->copy()->startOfDay();
        }


        if (
            $chartEndDate &&
            $monthEnd->gt(
                $chartEndDate->copy()->endOfDay()
            )
        ) {

            $monthEnd =
                $chartEndDate->copy()->endOfDay();
        }


        $monthlyRequestLabels[] =
            $currentMonth->format('M Y');


        $monthlyRequestCounts[] =
            DocumentRequest::whereBetween(
                'date_received',
                [
                    $monthStart,
                    $monthEnd,
                ]
            )->count();


        $currentMonth->addMonth();
    }
}


/*
|--------------------------------------------------------------------------
| Short Custom Date Range
|--------------------------------------------------------------------------
|
| For custom ranges of 31 days or less,
| display requests per day.
|
*/

elseif (
    $range === 'custom' &&
    $chartStartDate &&
    $chartEndDate
) {

    $currentDate =
        $chartStartDate->copy()->startOfDay();

    $lastDate =
        $chartEndDate->copy()->startOfDay();


    while (
        $currentDate->lte($lastDate)
    ) {

        $monthlyRequestLabels[] =
            $currentDate->format('M d');


        $monthlyRequestCounts[] =
            DocumentRequest::whereDate(
                'date_received',
                $currentDate
            )->count();


        $currentDate->addDay();
    }
}
        /*
        |--------------------------------------------------------------------------
        | User / Organization Counts
        |--------------------------------------------------------------------------
        */

        $userCount = User::count();

        $organizationCount = Organization::count();


        /*
        |--------------------------------------------------------------------------
        | Priority
        |--------------------------------------------------------------------------
        */

        $highPriorityCount = DocumentRequest::whereIn(
            'priority',
            [
                'High',
                'Urgent',
            ]
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Due Date Monitoring
        |--------------------------------------------------------------------------
        */

        $dueTodayCount = DocumentRequest::whereDate(
            'due_date',
            today()
        )
        ->whereNotIn('status', [
            'Completed',
            'Archived',
        ])
        ->count();


        $dueSoonCount = DocumentRequest::whereBetween(
            'due_date',
            [
                today()->addDay(),
                today()->addDays(3),
            ]
        )
        ->whereNotIn('status', [
            'Completed',
            'Archived',
        ])
        ->count();


        $overdueCount = DocumentRequest::whereDate(
            'due_date',
            '<',
            today()
        )
        ->whereNotIn('status', [
            'Completed',
            'Archived',
        ])
        ->count();


        /*
        |--------------------------------------------------------------------------
        | Incoming / Assignment Monitoring
        |--------------------------------------------------------------------------
        */

        $unassignedCount = DocumentRequest::where(
            'status',
            'Incoming'
        )
        ->whereNull('assigned_to')
        ->count();


        /*
        |--------------------------------------------------------------------------
        | My Work
        |--------------------------------------------------------------------------
        */

        $myAssignedCount = 0;
        $myOngoingCount = 0;

        if (auth()->check()) {

            $myAssignedCount = DocumentRequest::where(
                'assigned_to',
                auth()->id()
            )
            ->where('status', 'Assigned')
            ->count();


            $myOngoingCount = DocumentRequest::where(
                'assigned_to',
                auth()->id()
            )
            ->where('status', 'Ongoing')
            ->count();

        }


        /*
        |--------------------------------------------------------------------------
        | Dashboard View
        |--------------------------------------------------------------------------
        */
        $defaultVisibleStats = [
    'incoming',
    'ongoing',
    'completed',
];

$preference = auth()->user()->dashboardPreference;

$visibleStats = $preference?->visible_stats
    ?? $defaultVisibleStats;

        return view('dashboard.index', compact(

            'totalRequestCount',

            'incomingCount',
            'assignedCount',
            'ongoingCount',
            'completedCount',
            'archivedCount',

            'userCount',
            'organizationCount',

            'highPriorityCount',

            'dueTodayCount',
            'dueSoonCount',
            'overdueCount',

            'unassignedCount',

            'myAssignedCount',
            'myOngoingCount',

            'statusChart',
            'priorityChart',
            'requestTypeChart',
            'servicesChart',
            'organizationsChart',

            'monthlyRequestLabels',
            'monthlyRequestCounts',

            'completionRate',
            'overdueRate',
            'averageProcessingDays',
            'activeRequestCount',

            'range',
'startDate',
'endDate',
'visibleStats',

        ));
    }
}