<div class="card shadow-sm border-0">

    @isset($title)

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-bold">

                {{ $title }}

            </h5>

        </div>

    @endisset

    <div class="card-body">

        {{ $slot }}

    </div>

</div>