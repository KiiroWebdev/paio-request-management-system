@extends('layouts.master')

@section('page-title', 'Edit Request')

@section('content')

<div class="container-fluid">

    <div class="card shadow-sm">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h3 class="mb-1">
                        Edit Request
                    </h3>

                    <div class="text-muted">

                        {{ $documentRequest->control_no }}

                    </div>

                </div>

                <span class="badge
                    @if($documentRequest->status === 'Incoming')
                        bg-warning text-dark
                    @elseif($documentRequest->status === 'Assigned')
                        bg-info
                    @elseif($documentRequest->status === 'Ongoing')
                        bg-primary
                    @elseif($documentRequest->status === 'Completed')
                        bg-success
                    @else
                        bg-secondary
                    @endif
                ">

                    {{ $documentRequest->status }}

                </span>

            </div>

        </div>

        <div class="card-body">

            @if(session('success'))

                <div class="alert alert-success">

                    {{ session('success') }}

                </div>

            @endif

            @if(session('error'))

                <div class="alert alert-danger">

                    {{ session('error') }}

                </div>

            @endif

            @if($errors->any())

                <div class="alert alert-danger">

                    <strong>
                        Please correct the following errors:
                    </strong>

                    <ul class="mb-0 mt-2">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            <form
                method="POST"
                action="{{ route('requests.update', $documentRequest) }}"
                enctype="multipart/form-data">

                @csrf

                @method('PUT')


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

                            Organization

                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="organization_id"
                            id="organization_id"
                            class="form-select @error('organization_id') is-invalid @enderror">

                            <option value="">
                                Select Organization
                            </option>

                            @foreach($organizations as $organization)

                                <option
                                    value="{{ $organization->id }}"
                                    @selected(
                                        old(
                                            'organization_id',
                                            $documentRequest->organization_id
                                        ) == $organization->id
                                    )>

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

                            Requestor

                            <span class="text-danger">*</span>

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
                                    @selected(
                                        old(
                                            'contact_id',
                                            $documentRequest->contact_id
                                        ) == $contact->id
                                    )>

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

                            Title

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="title"
                            value="{{ old('title', $documentRequest->title) }}"
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

                            Subject

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="subject"
                            value="{{ old('subject', $documentRequest->subject) }}"
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
                        class="form-control @error('description') is-invalid @enderror">{{ old('description', $documentRequest->description) }}</textarea>

                    @error('description')

                        <div class="invalid-feedback">

                            {{ $message }}

                        </div>

                    @enderror

                </div>


                {{-- Date / Type / Priority --}}

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Date Received

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="date"
                            name="date_received"
                            value="{{ old(
                                'date_received',
                                optional($documentRequest->date_received)->format('Y-m-d')
                            ) }}"
                            class="form-control @error('date_received') is-invalid @enderror"
                            required>

                        @error('date_received')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Request Type

                        </label>

                        <select
                            name="request_type"
                            class="form-select @error('request_type') is-invalid @enderror">

                            <option
                                value="Internal"
                                @selected(
                                    old(
                                        'request_type',
                                        $documentRequest->request_type
                                    ) === 'Internal'
                                )>

                                Internal

                            </option>

                            <option
                                value="External"
                                @selected(
                                    old(
                                        'request_type',
                                        $documentRequest->request_type
                                    ) === 'External'
                                )>

                                External

                            </option>

                        </select>

                        @error('request_type')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Priority

                        </label>

                        <select
                            name="priority"
                            class="form-select @error('priority') is-invalid @enderror">

                            @foreach([
                                'Low',
                                'Normal',
                                'High',
                                'Urgent'
                            ] as $priority)

                                <option
                                    value="{{ $priority }}"
                                    @selected(
                                        old(
                                            'priority',
                                            $documentRequest->priority
                                        ) === $priority
                                    )>

                                    {{ $priority }}

                                </option>

                            @endforeach

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
                {{-- Selected Services --}}
                {{-- ========================================================= --}}

                <h5 class="mb-3">

                    Services

                </h5>

                <div class="mb-4">

                    @forelse($documentRequest->services as $service)

                        <span class="badge bg-primary fs-6 me-2 mb-2">

                            <i class="{{ $service->icon ?? 'bi bi-tag' }}"></i>

                            {{ $service->name }}

                        </span>

                    @empty

                        <span class="text-muted">

                            No services assigned.

                        </span>

                    @endforelse

                </div>


                {{-- ========================================================= --}}
                {{-- Dynamic Service Fields --}}
                {{-- ========================================================= --}}

                @foreach($documentRequest->services as $service)

                    <div class="card border mb-4">

                        <div class="card-header">

                            <strong>

                                <i class="{{ $service->icon ?? 'bi bi-list-check' }}"></i>

                                {{ $service->name }}

                            </strong>

                        </div>

                        <div class="card-body">

                            @forelse(
                                $service->fields->where('active', true)
                                as $field
                            )

                                <x-dynamic.field
                                    :field="$field"
                                    :name="'answers['.$field->id.']'"
                                    :value="old(
                                        'answers.'.$field->id,
                                        $answers[$field->id] ?? null
                                    )"
                                />

                            @empty

                                <div class="text-muted">

                                    This service has no dynamic fields.

                                </div>

                            @endforelse

                        </div>

                    </div>

                @endforeach


                {{-- ========================================================= --}}
                {{-- Buttons --}}
                {{-- ========================================================= --}}

                <div class="d-flex justify-content-between mt-4">

                    <a
                        href="{{ route('requests.show', $documentRequest) }}"
                        class="btn btn-secondary">

                        <i class="bi bi-arrow-left"></i>

                        Cancel

                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary">

                        <i class="bi bi-save"></i>

                        Save Changes

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const organization =
        document.getElementById('organization_id');

    const contact =
        document.getElementById('contact_id');

    if (!organization || !contact) {
        return;
    }

    const allContacts =
        Array.from(contact.options);

    function filterContacts() {

        const selectedOrg =
            organization.value;

        const selectedContact =
            contact.value;

        contact.innerHTML = '';

        const placeholder =
            document.createElement('option');

        placeholder.value = '';

        placeholder.textContent =
            'Select Requestor';

        contact.appendChild(
            placeholder
        );

        allContacts.forEach(function (option) {

            if (option.value === '') {
                return;
            }

            if (
                option.dataset.organization ===
                selectedOrg
            ) {

                const clone =
                    option.cloneNode(true);

                if (
                    clone.value ===
                    selectedContact
                ) {

                    clone.selected = true;

                }

                contact.appendChild(clone);

            }

        });

    }

    organization.addEventListener(
        'change',
        filterContacts
    );

    filterContacts();

});

</script>

@endpush

@endsection