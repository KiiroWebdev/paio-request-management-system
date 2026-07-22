{{-- STEP 1 --}}
                <div class="wizard-page active">

    <div class="row">

        <div class="col-lg-8">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <h4 class="mb-3">

                        Select Required Services

                    </h4>

                    <div class="input-group mb-4">

                        <span class="input-group-text">

                            <i class="bi bi-search"></i>

                        </span>

                        <input
                            type="text"
                            id="serviceSearch"
                            class="form-control"
                            placeholder="Search services...">

                    </div>

                    <div class="row" id="serviceContainer">

                        @foreach($services as $service)

                            <div class="col-md-6 col-xl-4 mb-4 service-item">

                                <label class="service-card">

                                    <input
                                        type="checkbox"
                                        name="services[]"
                                        value="{{ $service->id }}"
                                        data-name="{{ $service->name }}">

                                    <div class="service-content">

                                        <div class="service-check">

                                            <i class="bi bi-check-lg"></i>

                                        </div>

                                        <i class="bi bi-grid service-icon"></i>

                                        <h6 class="mt-3">

                                            {{ $service->name }}

                                        </h6>

                                        <small class="text-muted">

                                            {{ $service->description }}

                                        </small>

                                    </div>

                                </label>

                            </div>

                        @endforeach

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="card shadow-sm border-0 sticky-top">

                <div class="card-body">

                    <h5>

                        Selected Services

                    </h5>

                    <hr>

                    <div id="selectedServices">

                        <div class="text-muted">

                            No service selected.

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

