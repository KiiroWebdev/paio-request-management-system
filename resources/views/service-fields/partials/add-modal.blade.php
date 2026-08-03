<div class="modal fade" id="addFieldModal" tabindex="-1">

    <div class="modal-dialog modal-lg">

        <form
            method="POST"
            action="{{ route('services.fields.store', $service) }}">

            @csrf

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">

                        Add Service Field

                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Label

                            </label>

                            <input
                                id="label"
                                type="text"
                                class="form-control"
                                name="label"
                                required>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Field Name

                            </label>

                            <input
                                id="field_name"
                                type="text"
                                class="form-control"
                                name="field_name"
                                readonly>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Field Type

                            </label>

                            <select
                                class="form-select"
                                name="field_type_id"
                                required>

                                @foreach($fieldTypes as $type)

                                    <option value="{{ $type->id }}">

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
                                class="form-control"
                                name="placeholder">

                        </div>

                        <div class="col-12 mb-3">

                            <label class="form-label">

                                Help Text

                            </label>

                            <textarea
                                class="form-control"
                                rows="2"
                                name="help_text"></textarea>

                        </div>

                        <div class="col-md-4">

                           

                        <div class="col-md-4 d-flex align-items-end">

                            <div class="form-check">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="required"
                                    value="1">

                                <label class="form-check-label">

                                    Required

                                </label>

                            </div>

                        </div>

                        <div class="col-md-4 d-flex align-items-end">

                            <div class="form-check">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    checked
                                    name="active"
                                    value="1">

                                <label class="form-check-label">

                                    Active

                                </label>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button
                        class="btn btn-primary">

                        Save Field

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

<script>

document.getElementById('label').addEventListener('keyup', function(){

    document.getElementById('field_name').value = this.value
        .toLowerCase()
        .replace(/[^a-z0-9]+/g,'_')
        .replace(/^_|_$/g,'');

});

</script>