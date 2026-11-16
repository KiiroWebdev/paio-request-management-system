<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportSummarySheet implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    protected array $summary;
    protected array $statuses;
    protected array $priorities;
    protected array $requestTypes;
    protected Collection $services;
    protected Collection $organizations;

    public function __construct(
        array $summary,
        array $statuses,
        array $priorities,
        array $requestTypes,
        Collection $services,
        Collection $organizations
    ) {
        $this->summary = $summary;
        $this->statuses = $statuses;
        $this->priorities = $priorities;
        $this->requestTypes = $requestTypes;
        $this->services = $services;
        $this->organizations = $organizations;
    }

    public function title(): string
    {
        return 'Report Summary';
    }

    public function headings(): array
    {
        return ['Category', 'Description', 'Total'];
    }

    public function collection(): Collection
    {
        $rows = collect();

        $rows->push(['OVERVIEW', '', '']);
        $rows->push(['Total Requests', '', $this->summary['total'] ?? 0]);
        $rows->push(['Incoming', '', $this->summary['incoming'] ?? 0]);
        $rows->push(['Ongoing', '', $this->summary['ongoing'] ?? 0]);
        $rows->push(['Done', '', $this->summary['done'] ?? 0]);
        $rows->push(['Archived', '', $this->summary['archived'] ?? 0]);

        $rows->push(['', '', '']);
        $rows->push(['STATUS DISTRIBUTION', '', '']);

        foreach ($this->statuses as $name => $total) {
            $rows->push(['Status', $name, $total]);
        }

        $rows->push(['', '', '']);
        $rows->push(['PRIORITY DISTRIBUTION', '', '']);

        foreach ($this->priorities as $name => $total) {
            $rows->push(['Priority', $name, $total]);
        }

        $rows->push(['', '', '']);
        $rows->push(['REQUEST TYPE DISTRIBUTION', '', '']);

        foreach ($this->requestTypes as $name => $total) {
            $rows->push(['Request Type', $name, $total]);
        }

        $rows->push(['', '', '']);
        $rows->push(['TOP REQUESTED SERVICES', '', '']);

        foreach ($this->services as $service) {
            $rows->push(['Service', $service->name, $service->total]);
        }

        $rows->push(['', '', '']);
        $rows->push(['TOP ORGANIZATIONS SERVED', '', '']);

        foreach ($this->organizations as $organization) {
            $rows->push([
                'Organization',
                $organization->name,
                $organization->total
            ]);
        }

        return $rows;
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->getStyle('A1:C1')->getFont()->setBold(true);
        $sheet->getStyle('A1:C1')->getFill()
            ->setFillType('solid')
            ->getStartColor()->setRGB('DCE6F1');

        foreach ($sheet->getColumnIterator() as $column) {
            $sheet->getColumnDimension($column->getColumnIndex())
                ->setAutoSize(true);
        }

        return [];
    }
}
