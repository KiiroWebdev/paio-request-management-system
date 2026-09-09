<div class="card shadow-sm">

    <div class="card-body">

        <div class="row">

            {{-- Label --}}
            <div class="col-md-6 mb-3">

                <label class="form-label fw-semibold">
                    Label
                    <span class="text-danger">*</span>
                </label>

                <input
                    type="text"
                    name="label"
                    class="form-control"
                    value="{{ old('label', $field->label ?? '') }}"
                    placeholder="Example: Coverage"
                    required>

            </div>


            {{-- Field Name --}}
            <div class="col-md-6 mb-3">

                <label class="form-label fw-semibold">
                    Field Name
                    <span class="text-danger">*</span>
                </label>

                <input
                    type="text"
                    name="field_name"
                    class="form-control"
                    value="{{ old('field_name', $field->field_name ?? '') }}"
                    placeholder="Example: coverage"
                    required>

                <div class="form-text">
                    Use letters, numbers, underscores, or dashes.
                </div>

            </div>


            {{-- Field Type --}}
            <div class="col-md-6 mb-3">

                <label class="form-label fw-semibold">
                    Field Type
                    <span class="text-danger">*</span>
                </label>

                <select
                    name="field_type_id"
                    id="field_type_id"
                    class="form-select"
                    required>

                    <option value="">
                        Select Field Type
                    </option>

                    @foreach($fieldTypes as $type)

                        <option
                            value="{{ $type->id }}"
                            @selected(
                                old(
                                    'field_type_id',
                                    $field->field_type_id ?? ''
                                ) == $type->id
                            )>

                            {{ $type->name }}

                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Placeholder --}}
            <div class="col-md-6 mb-3">

                <label class="form-label fw-semibold">
                    Placeholder
                </label>

                <input
                    type="text"
                    name="placeholder"
                    class="form-control"
                    value="{{ old('placeholder', $field->placeholder ?? '') }}"
                    placeholder="Example: Enter coverage details">

            </div>


            {{-- Help Text --}}
            <div class="col-md-12 mb-3">

                <label class="form-label fw-semibold">
                    Help Text
                </label>

                <textarea
                    name="help_text"
                    class="form-control"
                    rows="3"
                    placeholder="Optional instructions shown below the field">{{ old('help_text', $field->help_text ?? '') }}</textarea>

            </div>


            {{-- Required --}}
            <div class="col-md-6 mb-3">

                <div class="form-check">

                    <input
                        type="checkbox"
                        name="required"
                        value="1"
                        class="form-check-input"
                        id="required"
                        @checked(old('required', $field->required ?? false))>

                    <label
                        class="form-check-label fw-semibold"
                        for="required">

                        Required

                    </label>

                </div>

                <div class="form-text">
                    Users must provide a value for this field.
                </div>

            </div>


            {{-- Active --}}
            <div class="col-md-6 mb-3">

                <div class="form-check">

                    <input
                        type="checkbox"
                        name="active"
                        value="1"
                        class="form-check-input"
                        id="active"
                        @checked(old('active', $field->active ?? true))>

                    <label
                        class="form-check-label fw-semibold"
                        for="active">

                        Active

                    </label>

                </div>

                <div class="form-text">
                    Active fields will appear on the request form.
                </div>

            </div>

        </div>

    </div>

</div>