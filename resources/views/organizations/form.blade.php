<div class="row">

    <div class="col-md-8 mb-3">

        <label class="form-label">
            Organization Name
        </label>

        <input
            type="text"
            name="name"
            class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $organization->name ?? '') }}"
            required>

        @error('name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

    <div class="col-md-4 mb-3">

        <label class="form-label">
            Short Name
        </label>

        <input
            type="text"
            name="short_name"
            class="form-control"
            value="{{ old('short_name', $organization->short_name ?? '') }}">

    </div>

</div>

<div class="mb-3">

    <label class="form-label">
        Organization Type
    </label>

    <select
        name="type"
        class="form-select"
        required>

        <option value="">Select Type</option>

        @php

        $types = [

            'Department',
            'Office',
            'Division',
            'Section',
            'Committee',

            'Barangay',

            'National Agency',

            'GOCC',

            'School',

            'University',

            'Hospital',

            'Private Company',

            'NGO',

            "People's Organization",

            'Media',

            'Individual',

            'Other',

        ];

        @endphp

        @foreach($types as $type)

            <option
                value="{{ $type }}"
                @selected(old('type', $organization->type ?? '') == $type)>

                {{ $type }}

            </option>

        @endforeach

    </select>

</div>

<div class="row">

    <div class="col-md-6">

        <div class="form-check mb-3">

            <input
                type="checkbox"
                class="form-check-input"
                id="is_internal"
                name="is_internal"
                value="1"
                {{ old('is_internal', $organization->is_internal ?? false) ? 'checked' : '' }}>

            <label
                class="form-check-label"
                for="is_internal">

                Internal Organization

            </label>

        </div>

    </div>

    <div class="col-md-6">

        <div class="form-check mb-3">

            <input
                type="checkbox"
                class="form-check-input"
                id="is_active"
                name="is_active"
                value="1"
                {{ old('is_active', $organization->is_active ?? true) ? 'checked' : '' }}>

            <label
                class="form-check-label"
                for="is_active">

                Active

            </label>

        </div>

    </div>

</div>

<div class="mb-3">

    <label class="form-label">

        Address

    </label>

    <textarea
        name="address"
        rows="3"
        class="form-control">{{ old('address', $organization->address ?? '') }}</textarea>

</div>

<div class="row">

    <div class="col-md-4 mb-3">

        <label class="form-label">
            Telephone
        </label>

        <input
            type="text"
            name="telephone"
            class="form-control"
            value="{{ old('telephone', $organization->telephone ?? '') }}">

    </div>

    <div class="col-md-4 mb-3">

        <label class="form-label">
            Email
        </label>

        <input
            type="email"
            name="email"
            class="form-control"
            value="{{ old('email', $organization->email ?? '') }}">

    </div>

    <div class="col-md-4 mb-3">

        <label class="form-label">
            Website
        </label>

        <input
            type="url"
            name="website"
            class="form-control"
            value="{{ old('website', $organization->website ?? '') }}">

    </div>

</div>

<div class="text-end">

    <a
        href="{{ route('organizations.index') }}"
        class="btn btn-secondary">

        Cancel

    </a>

    <button
        class="btn btn-primary">

        <i class="bi bi-check-circle me-1"></i>

        Save Organization

    </button>

</div>