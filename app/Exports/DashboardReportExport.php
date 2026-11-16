<?php

namespace App\Exports;

use App\Models\DocumentRequest;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Export;

class DashboardReportExport implements Export, WithMultipleSheets
{
    protected $startDate;
    protected $endDate;
    protected $serviceId;

    public function __construct($startDate, $endDate, $serviceId = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->serviceId = $serviceId;
    }

    public function sheets(): array
    {
        $requestsQuery = DocumentRequest::query();

        // Apply the selected service filter to the report data.
if ($this->serviceId) {
    $requestsQuery->whereHas('services', function ($query) {
        $query->where('services.id', $this->serviceId);
    });
}

        if ($this->startDate && $this->endDate) {
            $requestsQuery->whereBetween('date_received', [
                $this->startDate,
                $this->endDate,
            ]);
        }

        $summary = [
            'total' => (clone $requestsQuery)->count(),

            'incoming' => (clone $requestsQuery)
                ->where('status', 'Incoming')->count(),

            'ongoing' => (clone $requestsQuery)
                ->where('status', 'Ongoing')->count(),

            'done' => (clone $requestsQuery)
                ->where('status', 'Done')->count(),

            'archived' => (clone $requestsQuery)
                ->where('status', 'Archived')->count(),
        ];

        $statuses = (clone $requestsQuery)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $priorities = (clone $requestsQuery)
            ->select('priority', DB::raw('COUNT(*) as total'))
            ->groupBy('priority')
            ->pluck('total', 'priority')
            ->toArray();

        $requestTypes = (clone $requestsQuery)
            ->select('request_type', DB::raw('COUNT(*) as total'))
            ->groupBy('request_type')
            ->pluck('total', 'request_type')
            ->toArray();

        $servicesQuery = DB::table('request_service')
            ->join('services', 'services.id', '=', 'request_service.service_id')
            ->join('requests', 'requests.id', '=', 'request_service.request_id')
            ->select(
                'services.name',
                DB::raw('COUNT(request_service.id) as total')
            );

            if ($this->serviceId) {
    $servicesQuery->where('request_service.service_id', $this->serviceId);
}

        if ($this->startDate && $this->endDate) {
            $servicesQuery->whereBetween('requests.date_received', [
                $this->startDate,
                $this->endDate,
            ]);
        }

        $services = $servicesQuery
            ->groupBy('services.id', 'services.name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $organizationsQuery = DB::table('requests')
            ->join('organizations', 'organizations.id', '=', 'requests.organization_id')
            ->select(
                'organizations.name',
                DB::raw('COUNT(requests.id) as total')
            );

            if ($this->serviceId) {
    $organizationsQuery->whereExists(function ($query) {
        $query->select(DB::raw(1))
            ->from('request_service')
            ->whereColumn('request_service.request_id', 'requests.id')
            ->where('request_service.service_id', $this->serviceId);
    });
}

        if ($this->startDate && $this->endDate) {
            $organizationsQuery->whereBetween('requests.date_received', [
                $this->startDate,
                $this->endDate,
            ]);
        }

        $organizations = $organizationsQuery
            ->groupBy('organizations.id', 'organizations.name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return [
            new ReportSummarySheet(
                $summary,
                $statuses,
                $priorities,
                $requestTypes,
                $services,
                $organizations
            ),

            new RequestDetailsSheet(
                $this->startDate,
                $this->endDate,
                $this->serviceId
            ),
        ];
    }
}

