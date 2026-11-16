@extends('layouts.master')

@section('page-title', $service->name)

@section('content')

<div class="container">

    <div class="card shadow-sm">

        <div class="card-header">

           
<h3>Submit a Request</h3>

<p class="text-muted mb-0">
    Select one or more services, then complete the required information for each.
</p>

        </div>

        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

           <form method="POST"
      action="{{ route('request-wizard.store', $service) }}"
      enctype="multipart/form-data">

                @csrf

{{-- Multiple Service Selection --}}
<div class="mb-4">
    <h5 class="mb-3">Select Required Services</h5>

    @error('services')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="row">
        @foreach($services as $availableService)
            <div class="col-md-6 col-lg-4 mb-3">
                <label class="card h-100 p-3">
                    <div class="form-check">
                        <input
                            type="checkbox"
                            class="form-check-input service-checkbox"
                            name="services[]"
                            value="{{ $availableService->id }}"
                            data-service-id="{{ $availableService->id }}"
                            @checked(
                                in_array(
                                    $availableService->id,
                                    old('services', [$service->id])
                                )
                            )
                        >

                        <span class="form-check-label fw-semibold">
                            {{ $availableService->name }}
                        </span>
                    </div>

                    @if($availableService->description)
                        <small class="text-muted mt-2">
                            {{ $availableService->description }}
                        </small>
                    @endif
                </label>
            </div>
        @endforeach
    </div>
</div>

<hr class="my-4">


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
            @selected(old('organization_id') == $organization->id)>
            {{ $organization->name }}
        </option>
    @endforeach

    <option value="manual" @selected(old('organization_id') === 'manual')>
        + Enter Organization Manually
    </option>

</select>

        @error('organization_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

    <div class="col-12 mb-3" id="manualOrganizationContainer" hidden>

    <div class="card border-primary">
        <div class="card-header bg-light">
            <strong>New Organization Details</strong>
        </div>

        <div class="card-body">
            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Organization Name *</label>
                    <input
                        type="text"
                        name="new_organization_name"
                        class="form-control"
                        value="{{ old('new_organization_name') }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Short Name</label>
                    <input
                        type="text"
                        name="new_organization_short_name"
                        class="form-control"
                        value="{{ old('new_organization_short_name') }}">
                </div>

                <div class="col-md-6 mb-3">
    <label class="form-label">Organization Type</label>

    <select
        name="new_organization_type"
        class="form-select">

        <option value="">Select Organization Type</option>
        <option value="Department" @selected(old('new_organization_type') == 'Department')>Department</option>
        <option value="Office" @selected(old('new_organization_type') == 'Office')>Office</option>
        <option value="Division" @selected(old('new_organization_type') == 'Division')>Division</option>
        <option value="Section" @selected(old('new_organization_type') == 'Section')>Section</option>
        <option value="Barangay" @selected(old('new_organization_type') == 'Barangay')>Barangay</option>
        <option value="National Agency" @selected(old('new_organization_type') == 'National Agency')>National Agency</option>
        <option value="GOCC" @selected(old('new_organization_type') == 'GOCC')>GOCC</option>
        <option value="School" @selected(old('new_organization_type') == 'School')>School</option>
        <option value="University" @selected(old('new_organization_type') == 'University')>University</option>
        <option value="Hospital" @selected(old('new_organization_type') == 'Hospital')>Hospital</option>
        <option value="Private Company" @selected(old('new_organization_type') == 'Private Company')>Private Company</option>
        <option value="NGO" @selected(old('new_organization_type') == 'NGO')>NGO</option>
        <option value="Peoples Organization" @selected(old('new_organization_type') == 'Peoples Organization')>Peoples Organization</option>
        <option value="Media" @selected(old('new_organization_type') == 'Media')>Media</option>
        <option value="Individual" @selected(old('new_organization_type') == 'Individual')>Individual</option>
        <option value="Other" @selected(old('new_organization_type') == 'Other')>Other</option>

    </select>
</div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Telephone</label>
                    <input
                        type="text"
                        name="new_organization_telephone"
                        class="form-control"
                        value="{{ old('new_organization_telephone') }}">
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Address</label>
                    <textarea
                        name="new_organization_address"
                        class="form-control"
                        rows="2">{{ old('new_organization_address') }}</textarea>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input
                        type="email"
                        name="new_organization_email"
                        class="form-control"
                        value="{{ old('new_organization_email') }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Website</label>
                    <input
                        type="text"
                        name="new_organization_website"
                        class="form-control"
                        value="{{ old('new_organization_website') }}">
                </div>

            </div>
        </div>
    </div>

</div>

    
<div class="col-md-6 mb-3">

    {{-- Requestor Dropdown --}}
    <label class="form-label">
        Requestor <span class="text-danger">*</span>
    </label>

    <select
        name="contact_id"
        id="contact_id"
        class="form-select @error('contact_id') is-invalid @enderror">

        <option value="">Select Requestor</option>

        {{-- Existing Contacts --}}
        @foreach($contacts as $contact)
            <option
                value="{{ $contact->id }}"
                data-organization="{{ $contact->organization_id }}"
                @selected(old('contact_id') == $contact->id)>
                {{ $contact->full_name }}
            </option>
        @endforeach

        {{-- Manual Entry Option --}}
        <option value="manual" @selected(old('contact_id') === 'manual')>
            + Enter Requestor Manually
        </option>

    </select>

    @error('contact_id')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror


    {{-- Manual Contact Fields --}}
    <div id="manualContactContainer"
         class="mt-3 p-3 border rounded bg-light"
         style="display: none;">

        <h6 class="mb-3">New Requestor Information</h6>

        <div class="row">

            <div class="col-md-6 mb-3">
                <label class="form-label">First Name *</label>
                <input type="text" name="new_contact_first_name"
                    class="form-control"
                    value="{{ old('new_contact_first_name') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Middle Name</label>
                <input type="text" name="new_contact_middle_name"
                    class="form-control"
                    value="{{ old('new_contact_middle_name') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Last Name *</label>
                <input type="text" name="new_contact_last_name"
                    class="form-control"
                    value="{{ old('new_contact_last_name') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Suffix</label>
                <input type="text" name="new_contact_suffix"
                    class="form-control"
                    value="{{ old('new_contact_suffix') }}"
                    placeholder="Jr., Sr., III">
            </div>

            <div class="col-md-6 mb-3">
    <label class="form-label">
        Position / Designation
        <span class="text-danger">*</span>
    </label>

    <input
        type="text"
        name="new_contact_position"
        class="form-control @error('new_contact_position') is-invalid @enderror"
        value="{{ old('new_contact_position') }}"
        placeholder="e.g. Information Officer"
        required>

    @error('new_contact_position')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Office</label>
                <input type="text" name="new_contact_office"
                    class="form-control"
                    value="{{ old('new_contact_office') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="new_contact_email"
                    class="form-control"
                    value="{{ old('new_contact_email') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Mobile Number</label>
                <input type="text" name="new_contact_mobile"
                    class="form-control"
                    value="{{ old('new_contact_mobile') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Telephone</label>
                <input type="text" name="new_contact_telephone"
                    class="form-control"
                    value="{{ old('new_contact_telephone') }}">
            </div>

        </div>
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

<hr class="my-4">

{{-- ========================================================= --}}
{{-- Shared Event Information --}}
{{-- ========================================================= --}}

<div class="mb-4">
    <h5 class="mb-3">
        <i class="bi bi-calendar-event me-2"></i>
        Event Information
    </h5>

    <p class="text-muted">
        Event details are optional. Select the information you want to provide.
    </p>

    {{-- Include Event Date --}}
    <div class="form-check mb-3">
        <input
            class="form-check-input"
            type="checkbox"
            id="includeEventDate"
            name="include_event_date"
            value="1"
            @checked(old('include_event_date'))
        >

        <label class="form-check-label fw-semibold" for="includeEventDate">
            Include Event Date
        </label>
    </div>

    <div id="eventDateContainer" class="mb-3" hidden>
        <label for="eventDate" class="form-label">
            Event Date
        </label>

        <input
            type="date"
            class="form-control"
            id="eventDate"
            name="event_date"
            value="{{ old('event_date') }}"
        >

        @error('event_date')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>

    {{-- Include Event Schedule --}}
    <div class="form-check mb-3">
        <input
            class="form-check-input"
            type="checkbox"
            id="includeEventSchedule"
            name="include_event_schedule"
            value="1"
            @checked(old('include_event_schedule'))
        >

        <label class="form-check-label fw-semibold" for="includeEventSchedule">
            Include Event Schedule
        </label>
    </div>

    <div id="eventScheduleContainer" hidden>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <label class="form-label fw-semibold mb-0">
                Event Schedule Entries
            </label>

            <button
                type="button"
                class="btn btn-outline-primary btn-sm"
                id="addEventSchedule"
            >
                <i class="bi bi-plus-circle me-1"></i>
                Add Schedule
            </button>
        </div>

        <div id="eventScheduleRows"></div>

        @error('event_schedule')
            <div class="text-danger small">{{ $message }}</div>
        @enderror

        @error('event_schedule.*.date')
            <div class="text-danger small">{{ $message }}</div>
        @enderror

        @error('event_schedule.*.start_time')
            <div class="text-danger small">{{ $message }}</div>
        @enderror

        @error('event_schedule.*.end_time')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
</div>

<hr class="my-4">

{{-- ========================================================= --}}
{{-- Service Information --}}
{{-- ========================================================= --}}


<h5 class="mb-3">Service Information</h5>

<div id="serviceFieldsContainer">
    @foreach($services as $availableService)
        <div
            class="service-fields-section mb-4"
            data-service-id="{{ $availableService->id }}"
            @if($availableService->id !== $service->id) hidden @endif
        >
            <h6 class="fw-semibold mb-3">
                {{ $availableService->name }}
            </h6>

            @forelse($availableService->fields as $field)

    @if(!in_array(strtolower($field->field_name), [
        'event_schedule',
        'date_of_event',
        'schedule_of_event',
    ], true))

        <x-dynamic.field
            :field="$field"
            :name="'answers['.$field->id.']'"
            :value="old('answers.'.$field->id)"
        />

    @endif

@empty
                <p class="text-muted">
                    No additional fields are required for this service.
                </p>
            @endforelse
        </div>
    @endforeach
</div>


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


// ==========================================
// Shared Event Information
// ==========================================

const includeEventDate = document.getElementById('includeEventDate');
const eventDateContainer = document.getElementById('eventDateContainer');
const eventDateInput = document.getElementById('eventDate');

const includeEventSchedule = document.getElementById('includeEventSchedule');
const eventScheduleContainer = document.getElementById('eventScheduleContainer');
const eventScheduleRows = document.getElementById('eventScheduleRows');
const addEventScheduleButton = document.getElementById('addEventSchedule');

function updateEventDateVisibility() {
    const enabled = includeEventDate.checked;

    eventDateContainer.hidden = !enabled;
    eventDateInput.disabled = !enabled;

    if (!enabled) {
        eventDateInput.value = '';
    }
}

function updateEventScheduleVisibility() {
    const enabled = includeEventSchedule.checked;

    eventScheduleContainer.hidden = !enabled;

    eventScheduleRows.querySelectorAll('input').forEach(input => {
        input.disabled = !enabled;
    });

    if (!enabled) {
        eventScheduleRows.innerHTML = '';
    } else if (eventScheduleRows.children.length === 0) {
        addScheduleRow();
    }
}

function addScheduleRow() {
    const index = eventScheduleRows.children.length;

    const row = document.createElement('div');
    row.className = 'card border mb-3 event-schedule-row';

    row.innerHTML = `
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <strong>Schedule ${index + 1}</strong>

                <button type="button"
                    class="btn btn-outline-danger btn-sm remove-schedule">
                    Remove
                </button>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Date</label>
                    <input type="date"
                        class="form-control"
                        name="event_schedule[${index}][date]"
                        required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Start Time</label>
                    <input type="time"
                        class="form-control"
                        name="event_schedule[${index}][start_time]"
                        required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">End Time</label>
                    <input type="time"
                        class="form-control"
                        name="event_schedule[${index}][end_time]"
                        required>
                </div>
            </div>
        </div>
    `;

    row.querySelector('.remove-schedule').addEventListener('click', function () {
        row.remove();
        renumberScheduleRows();
    });

    eventScheduleRows.appendChild(row);
}

function renumberScheduleRows() {
    const rows = eventScheduleRows.querySelectorAll('.event-schedule-row');

    rows.forEach((row, index) => {
        row.querySelector('strong').textContent = `Schedule ${index + 1}`;

        row.querySelectorAll('input').forEach(input => {
            const field = input.name.match(/\[(date|start_time|end_time)\]/)[1];
            input.name = `event_schedule[${index}][${field}]`;
        });
    });
}

includeEventDate.addEventListener('change', updateEventDateVisibility);
includeEventSchedule.addEventListener('change', updateEventScheduleVisibility);

addEventScheduleButton.addEventListener('click', addScheduleRow);

updateEventDateVisibility();
updateEventScheduleVisibility();

    

    // Show fields for every selected service.
    const serviceCheckboxes = document.querySelectorAll('.service-checkbox');
    const serviceSections = document.querySelectorAll('.service-fields-section');

    function updateServiceFields() {
        const selectedServiceIds = Array.from(serviceCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.dataset.serviceId);

        serviceSections.forEach(section => {
            const isSelected = selectedServiceIds.includes(
                section.dataset.serviceId
            );

            section.hidden = !isSelected;

            section.querySelectorAll('input, select, textarea').forEach(input => {
                input.disabled = !isSelected;
            });
        });
    }

    serviceCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateServiceFields);
    });

    updateServiceFields();
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


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const organizationSelect = document.getElementById('organization_id');
    const contactSelect = document.getElementById('contact_id');

    const manualOrganizationContainer = document.getElementById('manualOrganizationContainer');
    const manualContactContainer = document.getElementById('manualContactContainer');

    if (!organizationSelect || !contactSelect) {
        return;
    }

    function toggleManualFields(container, show) {
        if (!container) return;

        container.hidden = !show;
        container.style.display = show ? '' : 'none';

        container.querySelectorAll('input, textarea, select').forEach(function (field) {
            field.disabled = !show;
        });
    }

    function updateOrganizationFields() {
        const isManualOrganization = organizationSelect.value === 'manual';

        toggleManualFields(manualOrganizationContainer, isManualOrganization);

        // A newly entered organization needs a newly entered contact.
        if (isManualOrganization) {
            contactSelect.value = 'manual';
        }

        updateContactFields();
    }

    function updateContactFields() {
        const organizationId = organizationSelect.value;
        const isManualOrganization = organizationId === 'manual';
        const isManualContact = contactSelect.value === 'manual';

        Array.from(contactSelect.options).forEach(function (option) {
            if (!option.dataset.organization) return;

            const belongsToOrganization =
                !isManualOrganization &&
                option.dataset.organization === organizationId;

            option.hidden = !belongsToOrganization;
            option.disabled = !belongsToOrganization;
        });

        // Do not allow an existing contact with a different organization.
        if (
            !isManualOrganization &&
            contactSelect.selectedOptions.length &&
            contactSelect.selectedOptions[0].dataset.organization &&
            contactSelect.selectedOptions[0].dataset.organization !== organizationId
        ) {
            contactSelect.value = '';
        }

        toggleManualFields(manualContactContainer, isManualContact);
    }

   organizationSelect.addEventListener('change', updateOrganizationFields);
contactSelect.addEventListener('change', updateContactFields);

// Apply the correct state when the page first loads.
updateOrganizationFields();



});
</script>
@endpush
