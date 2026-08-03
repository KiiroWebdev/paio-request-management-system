@props([
    'field',
    'name',
])

<div class="mb-3">

    <label for="{{ str_replace(['[', ']'], ['_', ''], $name) }}"
           class="form-label">

        {{ $field->label }}

        @if($field->required)
            <span class="text-danger">*</span>
        @endif

    </label>

    {{ $slot }}

    @error(str_replace(['answers[', ']'], ['answers.', ''], $name))
        <div class="text-danger small mt-1">
            {{ $message }}
        </div>
    @enderror

    @if($field->help_text)
        <div class="form-text">
            {{ $field->help_text }}
        </div>
    @endif

</div>