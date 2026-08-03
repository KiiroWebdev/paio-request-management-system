<div class="card shadow-sm">

    <div class="card-body">

        <div class="row">

            <div class="col-md-6 mb-3">

                <label class="form-label">
                    Label
                </label>

                <input
                    type="text"
                    name="label"
                    class="form-control"
                    value="{{ old('label', $field->label ?? '') }}"
                    required>

            </div>

            <div class="col-md-6 mb-3">

                <label class="form-label">
                    Field Name
                </label>

                <input
                    type="text"
                    id="field_name"
                    name="field_name"
                    class="form-control"
                    value="{{ old('field_name', $field->field_name ?? '') }}"
                    readonly>

            </div>

            <div class="col-md-6 mb-3">

                <label class="form-label">
                    Field Type
                </label>

                <select
                    name="field_type_id"
                    class="form-select">

                    @foreach($fieldTypes as $type)

                        <option
                            value="{{ $type->id }}"
                            @selected(old('field_type_id', $field->field_type_id ?? '') == $type->id)>

                            {{ $type->name }}

                        </option>

                    @endforeach

                </select>

            </div>

            <div class="col-md-6 mb-3">

                <label class="form-label">
                    Placeholder
                </label>

                <input
                    type="text"
                    name="placeholder"
                    class="form-control"
                    value="{{ old('placeholder', $field->placeholder ?? '') }}">

            </div>

            <div class="col-12 mb-3">

                <label class="form-label">
                    Help Text
                </label>

                <textarea
                    class="form-control"
                    rows="3"
                    name="help_text">{{ old('help_text', $field->help_text ?? '') }}</textarea>

            </div>

            <div class="col-md-6">

                <div class="form-check">

                    <input
                        type="checkbox"
                        class="form-check-input"
                        name="required"
                        value="1"
                        @checked(old('required', $field->required ?? false))>

                    <label class="form-check-label">
                        Required
                    </label>

                </div>

            </div>

            <div class="col-md-6">

                <div class="form-check">

                    <input
                        type="checkbox"
                        class="form-check-input"
                        name="active"
                        value="1"
                        @checked(old('active', $field->active ?? true))>

                    <label class="form-check-label">
                        Active
                    </label>

                </div>

            </div>

        </div>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const labelInput = document.querySelector('input[name="label"]');
    const fieldName = document.getElementById('field_name');

    if (!labelInput || !fieldName) {
        return;
    }

    function generateFieldName() {
        fieldName.value = labelInput.value
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9]+/g, '_')
            .replace(/^_+|_+$/g, '');
    }

    labelInput.addEventListener('input', generateFieldName);

    generateFieldName();
});
</script>

