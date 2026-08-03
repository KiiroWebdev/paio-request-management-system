<div class="modal fade" id="editFieldModal" tabindex="-1">

    <div class="modal-dialog modal-lg">

        <form method="POST" id="editFieldForm">

            @csrf
            @method('PUT')

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Edit Field
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
                                type="text"
                                id="edit_label"
                                name="label"
                                class="form-control"
                                required>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Field Name
                            </label>

                            <input
                                type="text"
                                id="edit_field_name"
                                name="field_name"
                                class="form-control"
                                readonly>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Field Type
                            </label>

                            <select
                                id="edit_field_type"
                                name="field_type_id"
                                class="form-select">

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
                                type="text"
                                id="edit_placeholder"
                                name="placeholder"
                                class="form-control">

                        </div>

                        <div class="col-12 mb-3">

                            <label class="form-label">
                                Help Text
                            </label>

                            <textarea
                                id="edit_help_text"
                                name="help_text"
                                rows="3"
                                class="form-control"></textarea>

                        </div>

                        <div class="col-md-6">

                            <div class="form-check">

                                <input
                                    id="edit_required"
                                    type="checkbox"
                                    name="required"
                                    value="1"
                                    class="form-check-input">

                                <label class="form-check-label">
                                    Required
                                </label>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-check">

                                <input
                                    id="edit_active"
                                    type="checkbox"
                                    name="active"
                                    value="1"
                                    class="form-check-input">

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
                        type="submit"
                        class="btn btn-primary">

                        Update Field

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

<script>

document.querySelectorAll('.editFieldBtn').forEach(function(button){

    button.addEventListener('click', function(){

        let id = this.dataset.id;

        document.getElementById('editFieldForm').action =
            "{{ url('services/'.$service->id.'/fields') }}/" + id;

        document.getElementById('edit_label').value =
            this.dataset.label;

        document.getElementById('edit_field_name').value =
            this.dataset.name;

        document.getElementById('edit_field_type').value =
            this.dataset.type;

        document.getElementById('edit_placeholder').value =
            this.dataset.placeholder;

        document.getElementById('edit_help_text').value =
            this.dataset.help;

        document.getElementById('edit_required').checked =
            this.dataset.required == 1;

        document.getElementById('edit_active').checked =
            this.dataset.active == 1;

    });

});

</script>