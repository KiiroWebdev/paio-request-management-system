@props([
    'active' => true,
    'activeText' => 'Active',
    'inactiveText' => 'Inactive',
])

@if($active)

    <span class="badge rounded-pill bg-success">

        <i class="bi bi-check-circle-fill me-1"></i>

        {{ $activeText }}

    </span>

@else

    <span class="badge rounded-pill bg-secondary">

        <i class="bi bi-x-circle-fill me-1"></i>

        {{ $inactiveText }}

    </span>

@endif