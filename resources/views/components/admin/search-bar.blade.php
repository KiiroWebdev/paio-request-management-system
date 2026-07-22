@props([
    'placeholder' => 'Search...',
    'buttonText' => 'Search',
])

<form method="GET" class="mb-4">

    <div class="row">

        <div class="col-md-5">

            <div class="input-group">

                <span class="input-group-text">

                    <i class="bi bi-search"></i>

                </span>

                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="{{ $placeholder }}"
                    value="{{ request('search') }}">

                <button
                    class="btn btn-outline-primary"
                    type="submit">

                    {{ $buttonText }}

                </button>

            </div>

        </div>

        {{ $slot }}

    </div>

</form>