@props([
    'field',
    'name',
    'value' => null,
    'readonly' => false,
])

<x-dynamic.base
    :field="$field"
    :name="$name">

    <input
        id="{{ str_replace(['[', ']'], ['_', ''], $name) }}"
        type="text"
        name="{{ $name }}"
        value="{{ $value }}"
        placeholder="{{ $field->placeholder }}"
        class="form-control @error(str_replace(['answers[', ']'], ['answers.', ''], $name)) is-invalid @enderror"
        @readonly($readonly)
        @required($field->required)>

</x-dynamic.base>