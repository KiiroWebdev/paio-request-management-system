<div class="card shadow-sm border-0">

    <div class="card-body">

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label">Organization *</label>

                <select
                    name="organization_id"
                    class="form-select"
                    required>

                    <option value="">Select Organization</option>

                    @foreach($organizations as $organization)

                        <option
                            value="{{ $organization->id }}"
                            @selected(old('organization_id', $contact->organization_id ?? '') == $organization->id)>

                            {{ $organization->name }}

                        </option>

                    @endforeach

                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">First Name *</label>

                <input
                    type="text"
                    name="first_name"
                    class="form-control"
                    value="{{ old('first_name', $contact->first_name ?? '') }}"
                    required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Middle Name</label>

                <input
                    type="text"
                    name="middle_name"
                    class="form-control"
                    value="{{ old('middle_name', $contact->middle_name ?? '') }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Last Name *</label>

                <input
                    type="text"
                    name="last_name"
                    class="form-control"
                    value="{{ old('last_name', $contact->last_name ?? '') }}"
                    required>
            </div>

            <div class="col-md-3">
                <label class="form-label">Suffix</label>

                <input
                    type="text"
                    name="suffix"
                    class="form-control"
                    value="{{ old('suffix', $contact->suffix ?? '') }}">
            </div>

            <div class="col-md-5">
                <label class="form-label">Position *</label>

                <input
                    type="text"
                    name="position"
                    class="form-control"
                    value="{{ old('position', $contact->position ?? '') }}"
                    required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Office</label>

                <input
                    type="text"
                    name="office"
                    class="form-control"
                    value="{{ old('office', $contact->office ?? '') }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Email</label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    value="{{ old('email', $contact->email ?? '') }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Mobile</label>

                <input
                    type="text"
                    name="mobile"
                    class="form-control"
                    value="{{ old('mobile', $contact->mobile ?? '') }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Telephone</label>

                <input
                    type="text"
                    name="telephone"
                    class="form-control"
                    value="{{ old('telephone', $contact->telephone ?? '') }}">
            </div>

            <div class="col-12">
                <label class="form-label">Remarks</label>

                <textarea
                    name="remarks"
                    rows="3"
                    class="form-control">{{ old('remarks', $contact->remarks ?? '') }}</textarea>
            </div>

            <div class="col-md-3">

                <div class="form-check mt-2">

                    <input
                        type="checkbox"
                        name="is_primary"
                        value="1"
                        class="form-check-input"
                        @checked(old('is_primary', $contact->is_primary ?? false))>

                    <label class="form-check-label">

                        Primary Contact

                    </label>

                </div>

            </div>

            <div class="col-md-3">

                <div class="form-check mt-2">

                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        class="form-check-input"
                        @checked(old('is_active', $contact->is_active ?? true))>

                    <label class="form-check-label">

                        Active

                    </label>

                </div>

            </div>

        </div>

    </div>

</div>