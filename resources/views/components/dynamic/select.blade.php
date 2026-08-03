@props([
    'field',
    'name',
    'value' => null,
])

<x-dynamic.base
    :field="$field"
    :name="$name">

    <select
        id="{{ str_replace(['[', ']'], ['_', ''], $name) }}"
        name="{{ $name }}"
        class="form-select"
        @required($field->required)>

        <option value="">-- Select --</option>

        @foreach($field->options as $option)

            <option
                value="{{ $option->value }}"
                @selected($value == $option->value)>

                {{ $option->label }}

            </option>

        @endforeach

    </select>

</x-dynamic.base>