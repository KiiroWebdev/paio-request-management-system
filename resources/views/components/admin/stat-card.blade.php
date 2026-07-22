@props([
    'title',
    'value' => 0,
    'icon' => 'bi-grid',
    'color' => 'primary',
])

@php

$colors = [

    'primary' => 'bg-primary',

    'success' => 'bg-success',

    'danger' => 'bg-danger',

    'warning' => 'bg-warning',

    'info' => 'bg-info',

    'secondary' => 'bg-secondary',

    'dark' => 'bg-dark',

];

$bg = $colors[$color] ?? 'bg-primary';

@endphp

<div class="card stat-card shadow-sm border-0 h-100">

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center">

            <div>

                <small class="text-muted">

                    {{ $title }}

                </small>

                <h2 class="fw-bold mb-0 mt-1">

                    {{ $value }}

                </h2>

            </div>

            <div class="stat-icon {{ $bg }}">

                <i class="bi {{ $icon }}"></i>

            </div>

        </div>

    </div>

</div>