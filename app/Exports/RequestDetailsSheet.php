<?php

namespace App\Exports;

use App\Models\DocumentRequest;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RequestDetailsSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles
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

    public function title(): string
    {
        return 'Request Details';
    }

    public function collection(): Collection
    {
        $query = DocumentRequest::query()
            ->with([
                'organization',
                'contact',
                'services',
                'receivedBy',
            ]);

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('date_received', [
                $this->startDate,
                $this->endDate,
            ]);
        }

        if ($this->serviceId) {
            $query->whereHas('services', function ($serviceQuery) {
                $serviceQuery->where('services.id', $this->serviceId);
            });
        }

        return $query
            ->orderBy('date_received')
            ->orderBy('control_no')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Control Number',
            'Date Received',
            'Request Title',
            'Subject',
            'Organization',
            'Contact Person',
            'Requested Services',
            'Request Type',
            'Priority',
            'Status',
            'Received By',
            'Due Date',
            'Completed Date',
        ];
    }

    public function map($request): array
    {
        return [
            $request->control_no,
            $request->date_received
                ? Carbon::parse($request->date_received)->format('M d, Y')
                : '',
            $request->title,
            $request->subject,
            $request->organization?->name ?? 'N/A',
            $request->contact?->full_name ?? 'N/A',
            $request->services->pluck('name')->implode(', '),
            $request->request_type,
            $request->priority,
            $request->status,
            $request->receivedBy?->name ?? 'N/A',
            $request->due_date
                ? Carbon::parse($request->due_date)->format('M d, Y')
                : '',
            $request->completed_at
                ? Carbon::parse($request->completed_at)->format('M d, Y')
                : '',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->getStyle('A1:M1')->getFont()->setBold(true);
        $sheet->getStyle('A1:M1')->getFill()
            ->setFillType('solid')
            ->getStartColor()->setRGB('DCE6F1');

        $sheet->freezePane('A2');
        $sheet->setAutoFilter('A1:M1');

        foreach (range('A', 'M') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return [];
    }
}

