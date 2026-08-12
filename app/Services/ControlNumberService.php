<?php

namespace App\Services;

use App\Models\DocumentRequest;

class ControlNumberService
{
    public function generate(): string
    {
        $year = now()->year;

        $latest = DocumentRequest::whereYear(
            'created_at',
            $year
        )
        ->latest('id')
        ->first();

        $next = $latest
            ? $latest->id + 1
            : 1;

        return sprintf(
            'PAIO-%d-%06d',
            $year,
            $next
        );
    }
}