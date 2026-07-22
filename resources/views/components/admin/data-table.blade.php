<div class="card shadow-sm border-0">

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                {{ $slot }}

            </table>

        </div>

        @isset($pagination)

            <div class="mt-3">

                {{ $pagination }}

            </div>

        @endisset

    </div>

</div>