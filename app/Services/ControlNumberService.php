<?php

namespace App\Services;

use App\Models\DocumentRequest;

class ControlNumberService
{
    public function generate(): string
    {
        $year = now()->format('Y');

        $lastRequest = DocumentRequest::query()
            ->whereYear('created_at', $year)
            ->latest('id')
            ->first();

        $next = 1;

        if ($lastRequest) {

            $parts = explode('-', $lastRequest->control_no);

            $next = (int) end($parts) + 1;
        }

        return sprintf(
            'PAIO-%s-%06d',
            $year,
            $next
        );
    }
}