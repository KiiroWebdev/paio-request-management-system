@csrf

<div class="row">

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Label <span class="text-danger">*</span>
        </label>

        <input
            id="label"
            type="text"
            name="label"
            class="form-control @error('label') is-invalid @enderror"
            value="{{ old('label', $field->label ?? '') }}"
            required>

        @error('label')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Field Name <span class="text-danger">*</span>
        </label>

        <input
            id="field_name"
            type="text"
            name="field_name"
            class="form-control @error('field_name') is-invalid @enderror"
            value="{{ old('field_name', $field->field_name ?? '') }}"
            required>

        @error('field_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

    </div>

</div>

<div class="row">

    <div class="col-md-4 mb-3">

        <label class="form-label">
            Field Type
        </label>

        <select
            id="field_type"
            name="field_type_id"
            class="form-select @error('field_type_id') is-invalid @enderror">

            @foreach($fieldTypes as $type)

                <option
                    value="{{ $type->id }}"
                    data-key="{{ $type->key }}"
                    @selected(old('field_type_id', $field->field_type_id ?? '') == $type->id)>

                    {{ $type->name }}

                </option>

            @endforeach

        </select>

    </div>

    <div class="col-md-4 mb-3">

        <label class="form-label">
            Display Order
        </label>

        <input
            type="number"
            name="display_order"
            class="form-control"
            value="{{ old('display_order', $field->display_order ?? 1) }}">

    </div>

    <div
        class="col-md-4 mb-3"
        id="placeholder-group">

        <label class="form-label">
            Placeholder
        </label>

        <input
            type="text"
            name="placeholder"
            class="form-control"
            value="{{ old('placeholder', $field->placeholder ?? '') }}">

    </div>

</div>

<div class="mb-3">

    <label class="form-label">

        Help Text

    </label>

    <textarea
        name="help_text"
        class="form-control"
        rows="3">{{ old('help_text', $field->help_text ?? '') }}</textarea>

</div>

<div class="form-check mb-2">

    <input
        class="form-check-input"
        type="checkbox"
        name="required"
        value="1"
        @checked(old('required', $field->required ?? false))>

    <label class="form-check-label">

        Required Field

    </label>

</div>

<div class="form-check mb-4">

    <input
        class="form-check-input"
        type="checkbox"
        name="active"
        value="1"
        @checked(old('active', $field->active ?? true))>

    <label class="form-check-label">

        Active

    </label>

</div>

<hr class="my-4">

<h5 class="mb-3">
    Live Preview
</h5>

<div
    id="field-preview"
    class="border rounded p-4 bg-light">

</div>

<div class="d-flex justify-content-end gap-2">

    <a
        href="{{ route('services.fields.index', $service) }}"
        class="btn btn-secondary">

        Cancel

    </a>

    <button
        type="submit"
        class="btn btn-primary">

        Save Field

    </button>

</div>

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const label = document.getElementById('label');
    const fieldName = document.getElementById('field_name');
    const fieldType = document.getElementById('field_type');
    const placeholderGroup = document.getElementById('placeholder-group');

    let manuallyEdited = fieldName.value !== '';

    // Auto-generate field name
    label.addEventListener('input', function () {

        if (manuallyEdited) {
            return;
        }

        fieldName.value = label.value
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9]+/g, '_')
            .replace(/^_+|_+$/g, '');

    });

    fieldName.addEventListener('input', function () {

        manuallyEdited = fieldName.value !== '';

    });

    // Show / Hide Placeholder
    function togglePlaceholder() {

        const key =
            fieldType.options[fieldType.selectedIndex].dataset.key;

        const usesPlaceholder = [
            'text',
            'textarea',
            'number'
        ];

        placeholderGroup.style.display =
            usesPlaceholder.includes(key)
                ? ''
                : 'none';

    }

    fieldType.addEventListener('change', togglePlaceholder);

    togglePlaceholder();

updatePreview();

label.addEventListener('input', updatePreview);

fieldType.addEventListener('change', updatePreview);

document
    .querySelector('[name="placeholder"]')
    .addEventListener('input', updatePreview);
});

function updatePreview() {

    const key =
        fieldType.options[fieldType.selectedIndex].dataset.key;

    const labelText =
        label.value || 'Field Label';

    const placeholder =
        document.querySelector('[name="placeholder"]').value;

    let html = '';

    switch (key) {

        case 'text':

            html = `
                <label class="form-label fw-bold">
                    ${labelText}
                </label>

                <input
                    class="form-control"
                    placeholder="${placeholder}">
            `;

            break;

        case 'textarea':

            html = `
                <label class="form-label fw-bold">
                    ${labelText}
                </label>

                <textarea
                    class="form-control"
                    rows="3"
                    placeholder="${placeholder}"></textarea>
            `;

            break;

        case 'number':

            html = `
                <label class="form-label fw-bold">
                    ${labelText}
                </label>

                <input
                    type="number"
                    class="form-control">
            `;

            break;

        case 'date':

            html = `
                <label class="form-label fw-bold">
                    ${labelText}
                </label>

                <input
                    type="date"
                    class="form-control">
            `;

            break;

        case 'time':

            html = `
                <label class="form-label fw-bold">
                    ${labelText}
                </label>

                <input
                    type="time"
                    class="form-control">
            `;

            break;

        case 'checkbox':

            html = `
                <label class="form-label fw-bold">
                    ${labelText}
                </label>

                <div class="form-check">

                    <input
                        class="form-check-input"
                        type="checkbox">

                    <label class="form-check-label">

                        Option 1

                    </label>

                </div>

            `;

            break;

        case 'dropdown':

            html = `
                <label class="form-label fw-bold">
                    ${labelText}
                </label>

                <select class="form-select">

                    <option>

                        Select...

                    </option>

                </select>

            `;

            break;

        case 'file':

            html = `
                <label class="form-label fw-bold">
                    ${labelText}
                </label>

                <input
                    type="file"
                    class="form-control">
            `;

            break;

        default:

            html = `
                <div class="text-muted">

                    Preview unavailable.

                </div>
            `;
    }

    document
        .getElementById('field-preview')
        .innerHTML = html;

}

</script>

@endpush