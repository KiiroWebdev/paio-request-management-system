@extends('layouts.master')

@section('page-title', $service->name)

@section('content')

<div class="container">

    <div class="card shadow-sm">

        <div class="card-header">

            <h3>{{ $service->name }}</h3>

            @if($service->description)
                <p class="text-muted mb-0">
                    {{ $service->description }}
                </p>
            @endif

        </div>

        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

           <form method="POST"
      action="{{ route('requests.store', $service) }}"
      enctype="multipart/form-data">

                @csrf

                {{-- ========================================================= --}}
{{-- Request Information --}}
{{-- ========================================================= --}}

<h5 class="mb-4">
    Request Information
</h5>

{{-- Organization & Requestor --}}
<div class="row">

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Organization <span class="text-danger">*</span>
        </label>

        <select
            name="organization_id"
            id="organization_id"
            class="form-select @error('organization_id') is-invalid @enderror">

            <option value="">Select Organization</option>

            @foreach($organizations as $organization)

                <option
                    value="{{ $organization->id }}"
                    data-organization="{{ $organization->id }}"
                    @selected(old('organization_id') == $organization->id)>

                    {{ $organization->name }}

                </option>

            @endforeach

        </select>

        @error('organization_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Requestor <span class="text-danger">*</span>
        </label>

        <select
            name="contact_id"
            id="contact_id"
            class="form-select @error('contact_id') is-invalid @enderror">

            <option value="">
                Select Requestor
            </option>

            @foreach($contacts as $contact)

                <option
                    value="{{ $contact->id }}"
                    data-organization="{{ $contact->organization_id }}"
                    @selected(old('contact_id') == $contact->id)>

                    {{ $contact->full_name }}

                </option>

            @endforeach

        </select>

        @error('contact_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

</div>

{{-- Title & Subject --}}
<div class="row">

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Title <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="title"
            value="{{ old('title') }}"
            class="form-control @error('title') is-invalid @enderror"
            required>

        @error('title')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Subject <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="subject"
            value="{{ old('subject') }}"
            class="form-control @error('subject') is-invalid @enderror"
            required>

        @error('subject')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

</div>

{{-- Description --}}
<div class="mb-3">

    <label class="form-label">
        Description
    </label>

    <textarea
        name="description"
        rows="4"
        class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>

    @error('description')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror

</div>

{{-- Date / Due Date / Type / Priority --}}
<div class="row">

    <div class="row g-3">

    {{-- Date Received --}}
    <div class="col-md-3">

        <label class="form-label">
            Date Received <span class="text-danger">*</span>
        </label>

        <input
            type="date"
            name="date_received"
            value="{{ old('date_received', now()->toDateString()) }}"
            class="form-control @error('date_received') is-invalid @enderror"
            required>

        @error('date_received')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

    {{-- Due Date --}}
    <div class="col-md-3">

        <label class="form-label">
            Due Date
        </label>

        <input
            type="date"
            name="due_date"
            value="{{ old('due_date') }}"
            class="form-control @error('due_date') is-invalid @enderror">

        @error('due_date')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

    {{-- Request Type --}}
    <div class="col-md-3">

        <label class="form-label">
            Request Type
        </label>

        <select
            name="request_type"
            class="form-select @error('request_type') is-invalid @enderror">

            <option value="Internal"
                @selected(old('request_type') == 'Internal')>
                Internal
            </option>

            <option value="External"
                @selected(old('request_type') == 'External')>
                External
            </option>

        </select>

        @error('request_type')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

    {{-- Priority --}}
    <div class="col-md-3">

        <label class="form-label">
            Priority
        </label>

        <select
            name="priority"
            class="form-select @error('priority') is-invalid @enderror">

            <option value="Low"
                @selected(old('priority') == 'Low')>
                Low
            </option>

            <option value="Normal"
                @selected(old('priority', 'Normal') == 'Normal')>
                Normal
            </option>

            <option value="High"
                @selected(old('priority') == 'High')>
                High
            </option>

            <option value="Urgent"
                @selected(old('priority') == 'Urgent')>
                Urgent
            </option>

        </select>

        @error('priority')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

</div>

<hr class="my-4">

{{-- ========================================================= --}}
{{-- Service Information --}}
{{-- ========================================================= --}}

<h5 class="mb-3">
    Service Information
</h5>

@foreach($service->fields as $field)

    <x-dynamic.field
        :field="$field"
        :name="'answers['.$field->id.']'"
        :value="old('answers.'.$field->id)"
    />

@endforeach

<hr class="my-4">

<div class="mb-4">
    <h5 class="mb-1">
        <i class="bi bi-paperclip me-2"></i>
        Attachments
    </h5>

    <p class="text-muted mb-3">
        Please upload the documents or files required for this request.
        At least one attachment is required.
    </p>

    <div id="attachmentRows">

        <div class="attachment-row card border mb-3">
            <div class="card-body">

                <div class="row g-3 align-items-end">

                    <div class="col-md-4">
                        <label class="form-label">
                            Attachment Type
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="attachments[0][attachment_type]"
                            class="form-select"
                            required>
                            <option value="">Select attachment type</option>
                            <option value="Request Letter">Request Letter</option>
                            <option value="Program">Program</option>
                            <option value="Layout">Layout</option>
                            <option value="Memorandum">Memorandum</option>
                            <option value="Photo">Photo</option>
                            <option value="Video">Video</option>
                            <option value="Document">Document</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>

                    <div class="col-md-7">
                        <label class="form-label">
                            File
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="file"
                            name="attachments[0][file]"
                            class="form-control"
                            required>

                        <div class="form-text">
                            Maximum file size: 20 MB
                        </div>
                    </div>

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

    <button
        type="button"
        id="addAttachment"
        class="btn btn-outline-primary">
        <i class="bi bi-plus-circle me-1"></i>
        Add Another File
    </button>
</div>

<div class="mt-4">

    <button
        type="submit"
        class="btn btn-primary">

        <i class="bi bi-send"></i>
        Submit Request

    </button>

    <a
        href="{{ route('request-wizard.services') }}"
        class="btn btn-secondary">

        Cancel

    </a>

</div>

</form>

</div>

</div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const organization = document.getElementById('organization_id');
    const contact = document.getElementById('contact_id');

    if (!organization || !contact) {
        return;
    }

    const allContacts = Array.from(contact.options);

    function filterContacts() {

        const selectedOrg = organization.value;
        const selectedContact = contact.value;

        contact.innerHTML = '';

        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = 'Select Requestor';
        contact.appendChild(placeholder);

        allContacts.forEach(function(option) {

            if (option.value === '') return;

            if (option.dataset.organization === selectedOrg) {

                const clone = option.cloneNode(true);

                if (clone.value === selectedContact) {
                    clone.selected = true;
                }

                contact.appendChild(clone);

            }

        });

    }

    organization.addEventListener('change', filterContacts);

    filterContacts();

});
</script>
@endpush

@endsection

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
                            class="form-select"
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
                            class="form-control"
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

        const button =
            event.target.closest('.remove-attachment');

        if (!button) {
            return;
        }

        const row =
            button.closest('.attachment-row');

        if (row) {
            row.remove();
        }

        updateRemoveButtons();
    });


    function updateRemoveButtons() {

        const rows =
            container.querySelectorAll('.attachment-row');

        rows.forEach(function (row) {

            const button =
                row.querySelector('.remove-attachment');

            button.disabled =
                rows.length === 1;

        });
    }

});
</script>
@endpush