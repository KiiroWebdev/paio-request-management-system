@props([
    'field',
    'name',
    'value' => null,
])

<x-dynamic.base
    :field="$field"
    :name="$name">

    <input
        id="{{ str_replace(['[', ']'], ['_', ''], $name) }}"
        type="date"
        name="{{ $name }}"
        value="{{ $value }}"
        class="form-control"
        @required($field->required)>

</x-dynamic.base>