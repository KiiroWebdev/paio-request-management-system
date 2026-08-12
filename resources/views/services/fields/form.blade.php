<div class="card shadow-sm">

    <div class="card-body">

        <div class="row">

            <div class="col-md-6 mb-3">

                <label class="form-label">
                    Label
                </label>

                <input
                    id="option_label"
                    type="text"
                    name="label"
                    class="form-control"
                    value="{{ old('label', $option->label ?? '') }}"
                    required>

            </div>

            <div class="col-md-6 mb-3">

                <label class="form-label">
                    Value
                </label>

                <input
                    id="option_value"
                    type="text"
                    name="value"
                    class="form-control"
                    value="{{ old('value', $option->value ?? '') }}"
                    readonly>

            </div>

            <div class="col-md-12">

                <div class="form-check">

                    <input
                        type="checkbox"
                        class="form-check-input"
                        name="active"
                        value="1"
                        @checked(old('active', $option->active ?? true))>

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

    const label = document.getElementById('option_label');
    const value = document.getElementById('option_value');

    if (!label || !value) return;

    function generateValue() {
        value.value = label.value
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9]+/g, '_')
            .replace(/^_+|_+$/g, '');
    }

    label.addEventListener('input', generateValue);
});
</script>