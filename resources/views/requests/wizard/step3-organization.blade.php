<div class="wizard-page">

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">

            <h4 class="mb-0">
                <i class="bi bi-buildings me-2"></i>
                Organization
            </h4>

            <small class="text-muted">
                Select the requesting organization.
            </small>

        </div>

        <div class="card-body">

            <div class="mb-4">

                <label class="form-label fw-semibold">

                    Organization

                </label>

                <select
                    class="form-select"
                    name="organization_id"
                    id="organizationSelect">

                    <option value="">
                        Select Organization
                    </option>

                    @foreach(\App\Models\Organization::orderBy('name')->get() as $organization)

                        <option value="{{ $organization->id }}">

                            {{ $organization->name }}

                        </option>

                    @endforeach

                </select>

            </div>

            <div
                id="organizationPreview"
                class="alert alert-light border d-none">

            </div>

        </div>

    </div>

</div>