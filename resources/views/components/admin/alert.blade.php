@if(session($type))

<div class="alert alert-{{ $type }} alert-dismissible fade show shadow-sm">

    <div class="d-flex align-items-center">

        @switch($type)

            @case('success')
                <i class="bi bi-check-circle-fill me-2"></i>
                @break

            @case('danger')
                <i class="bi bi-x-circle-fill me-2"></i>
                @break

            @case('warning')
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                @break

            @default
                <i class="bi bi-info-circle-fill me-2"></i>

        @endswitch

        <span>{{ session($type) }}</span>

    </div>

    <button
        type="button"
        class="btn-close"
        data-bs-dismiss="alert">
    </button>

</div>

@endif