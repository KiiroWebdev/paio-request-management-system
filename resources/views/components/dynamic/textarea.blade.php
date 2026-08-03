@props([
    'field',
    'name',
    'value' => null,
    'readonly' => false,
])

<x-dynamic.base
    :field="$field"
    :name="$name">

    <textarea
        id="{{ str_replace(['[', ']'], ['_', ''], $name) }}"
        name="{{ $name }}"
        rows="4"
        class="form-control @error(str_replace(['answers[', ']'], ['answers.', ''], $name)) is-invalid @enderror"
        placeholder="{{ $field->placeholder }}"
        @readonly($readonly)
        @required($field->required)>{{ $value }}</textarea>

</x-dynamic.base>