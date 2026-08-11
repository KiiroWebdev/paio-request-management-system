@props([
    'field',
    'name',
    'value' => [],
])

<x-dynamic.base
    :field="$field"
    :name="$name">

    @foreach($field->options as $option)

        <div class="form-check">

            <input
                class="form-check-input"
                type="checkbox"
                name="{{ $name }}[]"
                value="{{ $option->value }}"
                id="option{{ $option->id }}"
                @checked(is_array($value) && in_array($option->value, $value))>

            <label
                class="form-check-label"
                for="option{{ $option->id }}">

                {{ $option->label }}

            </label>

        </div>

    @endforeach

</x-dynamic.base>