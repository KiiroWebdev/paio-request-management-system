@props([
    'pagination' => null,
])

<div class="card shadow-sm border-0">

    <div class="card-body">

        <div class="table-responsive">

            {{ $slot }}

        </div>

        @if($pagination)

            <div class="mt-4">

                {{ $pagination }}

            </div>

        @endif

    </div>

</div>