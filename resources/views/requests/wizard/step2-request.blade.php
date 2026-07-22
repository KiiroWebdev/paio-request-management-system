<div class="wizard-page">

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">

            <h4 class="mb-0">
                <i class="bi bi-file-earmark-text me-2"></i>
                Request Information
            </h4>

            <small class="text-muted">
                Enter the basic information for this request.
            </small>

        </div>

        <div class="card-body">

            <div class="row g-4">

                {{-- Title --}}
                <div class="col-md-6">

                    <label class="form-label fw-semibold">

                        Request Title

                    </label>

                    <input
                        type="text"
                        class="form-control"
                        name="title"
                        placeholder="e.g. Photography for Nutrition Month">

                </div>

                {{-- Request Type --}}
                <div class="col-md-6">

                    <label class="form-label fw-semibold">

                        Request Type

                    </label>

                    <select
                        class="form-select"
                        name="request_type">

                        <option value="">Select Request Type</option>

                        <option value="Internal">Internal</option>

                        <option value="External">External</option>

                    </select>

                </div>

                {{-- Subject --}}
                <div class="col-12">

                    <label class="form-label fw-semibold">

                        Subject

                    </label>

                    <input
                        type="text"
                        class="form-control"
                        name="subject"
                        placeholder="Request Subject">

                </div>

                {{-- Description --}}
                <div class="col-12">

                    <label class="form-label fw-semibold">

                        Description

                    </label>

                    <textarea
                        class="form-control"
                        rows="5"
                        name="description"
                        placeholder="Describe the requested service..."></textarea>

                </div>

                {{-- Priority --}}
                <div class="col-md-4">

                    <label class="form-label fw-semibold">

                        Priority

                    </label>

                    <select
                        class="form-select"
                        name="priority">

                        <option value="Low">Low</option>

                        <option value="Normal" selected>Normal</option>

                        <option value="High">High</option>

                        <option value="Urgent">Urgent</option>

                    </select>

                </div>

                {{-- Date Received --}}
                <div class="col-md-4">

                    <label class="form-label fw-semibold">

                        Date Received

                    </label>

                    <input
                        type="date"
                        class="form-control"
                        name="date_received"
                        value="{{ now()->format('Y-m-d') }}">

                </div>

                {{-- Due Date --}}
                <div class="col-md-4">

                    <label class="form-label fw-semibold">

                        Due Date

                    </label>

                    <input
                        type="date"
                        class="form-control"
                        name="due_date">

                </div>

                {{-- Request Letter --}}
                <div class="col-12">

                    <div class="form-check form-switch">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="has_request_letter"
                            value="1"
                            id="hasLetter">

                        <label
                            class="form-check-label"
                            for="hasLetter">

                            Request letter attached

                        </label>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>