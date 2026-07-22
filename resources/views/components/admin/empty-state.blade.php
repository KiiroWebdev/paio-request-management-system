@props([
    'icon' => 'bi-folder',
    'title' => 'No Records Found',
    'message' => 'There are currently no records available.',
    'buttonText' => null,
    'buttonRoute' => null,
])

<tr>

    <td colspan="{{ $attributes->get('colspan', 5) }}" class="text-center py-5">

        <i class="bi {{ $icon }} display-4 text-secondary"></i>

        <h5 class="mt-3">

            {{ $title }}

        </h5>

        <p class="text-muted mb-4">

            {{ $message }}

        </p>

        @if($buttonText && $buttonRoute)

            <a
                href="{{ $buttonRoute }}"
                class="btn btn-primary">

                <i class="bi bi-plus-circle me-1"></i>

                {{ $buttonText }}

            </a>

        @endif

    </td>

</tr>