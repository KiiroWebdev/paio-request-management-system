@props([
    'field',
    'name',
    'value' => null,
])

<x-dynamic.base
    :field="$field"
    :name="$name">

    @foreach($field->options as $option)

        <div class="form-check">

            <input
                class="form-check-input"
                type="radio"
                name="{{ $name }}"
                value="{{ $option->value }}"
                id="radio{{ $option->id }}"
                @checked($value == $option->value)>

            <label
                class="form-check-label"
                for="radio{{ $option->id }}">

                {{ $option->label }}

            </label>

        </div>

    @endforeach

</x-dynamic.base>