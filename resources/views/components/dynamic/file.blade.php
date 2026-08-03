@props([
    'field',
    'name',
])

<x-dynamic.base
    :field="$field"
    :name="$name">

    <input
        id="{{ str_replace(['[', ']'], ['_', ''], $name) }}"
        type="file"
        name="{{ $name }}"
        class="form-control"
        @required($field->required)>

</x-dynamic.base>