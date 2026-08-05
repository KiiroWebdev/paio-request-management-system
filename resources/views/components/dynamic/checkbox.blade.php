@props([
    'field',
    'name',
    'value' => [],
])

<x-dynamic.base
    :field="$field"
    :name="$name">

    <div class="alert alert-success mb-2">
        Checkbox component loaded!
    </div>

    <p>Options count: {{ $field->options->count() }}</p>

    @foreach($field->options as $option)

        <div class="form-check">

            <input
                type="checkbox"
                class="form-check-input"
                name="{{ $name }}[]"
                value="{{ $option->value }}">

            <label class="form-check-label">

                {{ $option->label }}

            </label>

        </div>

    @endforeach

</x-dynamic.base>