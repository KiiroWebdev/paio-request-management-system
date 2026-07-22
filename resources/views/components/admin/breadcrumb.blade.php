<nav aria-label="breadcrumb" class="mb-4">

    <ol class="breadcrumb mb-0">

        <li class="breadcrumb-item">

            <a href="{{ route('dashboard') }}">

                Dashboard

            </a>

        </li>

        @foreach($items as $item)

            @if($loop->last)

                <li class="breadcrumb-item active">

                    {{ $item }}

                </li>

            @else

                <li class="breadcrumb-item">

                    {{ $item }}

                </li>

            @endif

        @endforeach

    </ol>

</nav>