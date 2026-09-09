{{-- ========================================================= --}}
{{-- STEP 6 — ATTACHMENTS --}}
{{-- ========================================================= --}}

<div class="wizard-page">

    <div class="mb-4">

        <h4 class="mb-1">
            <i class="bi bi-paperclip me-2"></i>
            Attachments
        </h4>

        <p class="text-muted mb-0">
            Please upload the documents or files required for this request.
            At least one attachment is required.
        </p>

    </div>

    <div id="attachmentRows">

        <div class="attachment-row card border mb-3">

            <div class="card-body">

                <div class="row g-3 align-items-end">

                    {{-- Attachment Type --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Attachment Type
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="attachments[0][attachment_type]"
                            class="form-select attachment-type"
                            required>

                            <option value="">
                                Select attachment type
                            </option>

                            <option value="Request Letter">
                                Request Letter
                            </option>

                            <option value="Program">
                                Program
                            </option>

                            <option value="Layout">
                                Layout
                            </option>

                            <option value="Memorandum">
                                Memorandum
                            </option>

                            <option value="Photo">
                                Photo
                            </option>

                            <option value="Video">
                                Video
                            </option>

                            <option value="Document">
                                Document
                            </option>

                            <option value="Others">
                                Others
                            </option>

                        </select>

                    </div>

                    {{-- File --}}
                    <div class="col-md-7">

                        <label class="form-label">
                            File
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="file"
                            name="attachments[0][file]"
                            class="form-control attachment-file"
                            required>

                        <div class="form-text">
                            Maximum file size: 20 MB
                        </div>

                    </div>

                    {{-- Remove --}}
                    <div class="col-md-1">

                        <button
                            type="button"
                            class="btn btn-outline-danger remove-attachment"
                            disabled>

                            <i class="bi bi-trash"></i>

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Add another --}}
    <button
        type="button"
        id="addAttachment"
        class="btn btn-outline-primary">

        <i class="bi bi-plus-circle me-1"></i>
        Add Another File

    </button>

    <div class="alert alert-info mt-4 mb-0">

        <i class="bi bi-info-circle me-2"></i>

        You may upload multiple attachments. Each file can have its own
        attachment type.

    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const container = document.getElementById('attachmentRows');
    const addButton = document.getElementById('addAttachment');

    if (!container || !addButton) {
        return;
    }

    let attachmentIndex = 1;

    addButton.addEventListener('click', function () {

        const row = document.createElement('div');

        row.className = 'attachment-row card border mb-3';

        row.innerHTML = `
            <div class="card-body">

                <div class="row g-3 align-items-end">

                    <div class="col-md-4">

                        <label class="form-label">
                            Attachment Type
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="attachments[${attachmentIndex}][attachment_type]"
                            class="form-select attachment-type"
                            required>

                            <option value="">
                                Select attachment type
                            </option>

                            <option value="Request Letter">
                                Request Letter
                            </option>

                            <option value="Program">
                                Program
                            </option>

                            <option value="Layout">
                                Layout
                            </option>

                            <option value="Memorandum">
                                Memorandum
                            </option>

                            <option value="Photo">
                                Photo
                            </option>

                            <option value="Video">
                                Video
                            </option>

                            <option value="Document">
                                Document
                            </option>

                            <option value="Others">
                                Others
                            </option>

                        </select>

                    </div>

                    <div class="col-md-7">

                        <label class="form-label">
                            File
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="file"
                            name="attachments[${attachmentIndex}][file]"
                            class="form-control attachment-file"
                            required>

                        <div class="form-text">
                            Maximum file size: 20 MB
                        </div>

                    </div>

                    <div class="col-md-1">

                        <button
                            type="button"
                            class="btn btn-outline-danger remove-attachment">

                            <i class="bi bi-trash"></i>

                        </button>

                    </div>

                </div>

            </div>
        `;

        container.appendChild(row);

        attachmentIndex++;

        updateRemoveButtons();

    });

    container.addEventListener('click', function (event) {

        const button = event.target.closest('.remove-attachment');

        if (!button) {
            return;
        }

        const row = button.closest('.attachment-row');

        if (row) {
            row.remove();
        }

        updateRemoveButtons();

    });

    function updateRemoveButtons() {

        const rows = container.querySelectorAll('.attachment-row');

        rows.forEach((row) => {

            const button = row.querySelector('.remove-attachment');

            button.disabled = rows.length === 1;

        });

    }

});
</script>
@endpush