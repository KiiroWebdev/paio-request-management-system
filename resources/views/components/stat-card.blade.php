@props([
    'title',
    'value',
    'icon',
    'href' => null,
])

<div class="col-12 col-sm-6 col-xl-4">

    @if($href)

        <a
            href="{{ $href }}"
            class="text-decoration-none text-reset">

            <div class="card stat-card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted">
                                {{ $title }}
                            </small>

                            <h2 class="fw-bold mt-2 mb-0">
                                {{ $value }}
                            </h2>

                        </div>

                        <div class="stat-icon">

                            <i class="bi {{ $icon }}"></i>

                        </div>

                    </div>

                </div>

            </div>

        </a>

    @else

        <div class="card stat-card border-0 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted">
                            {{ $title }}
                        </small>

                        <h2 class="fw-bold mt-2 mb-0">
                            {{ $value }}
                        </h2>

                    </div>

                    <div class="stat-icon">

                        <i class="bi {{ $icon }}"></i>

                    </div>

                </div>

            </div>

        </div>

    @endif

</div>