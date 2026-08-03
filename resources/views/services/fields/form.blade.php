@csrf

<div class="row">

    <div class="col-md-6 mb-3">

        <label class="form-label">

            Label <span class="text-danger">*</span>

        </label>

        <input
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
            name="field_type_id"
            class="form-select @error('field_type_id') is-invalid @enderror">

            @foreach($fieldTypes as $type)

                <option
                    value="{{ $type->id }}"
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

    <div class="col-md-4 mb-3">

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