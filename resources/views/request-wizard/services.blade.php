@extends('layouts.master')

@section('page-title', 'New Request')

@section('content')

<div class="container">

    <div class="mb-4">

        <h2 class="fw-bold">
            Create New Request
        </h2>

        <p class="text-muted">
            Select the service you want to request.
        </p>

    </div>

    <div class="row g-4">

        @forelse($services as $service)

            <div class="col-md-6 col-lg-4">

                <div class="card shadow-sm h-100 border-0">

                    <div class="card-body d-flex flex-column">

                        <h4 class="fw-bold">

                            {{ $service->name }}

                        </h4>

                        <p class="text-muted flex-grow-1">

                            {{ $service->description }}

                        </p>

                        <a
                            href="{{ route('request-wizard.create', $service) }}"
                            class="btn btn-primary mt-3">

                            <i class="bi bi-plus-circle"></i>

                            Start Request

                        </a>

                    </div>

                </div>

            </div>

        @empty

            <div class="col-12">

                <div class="alert alert-warning">

                    No services are currently available.

                </div>

            </div>

        @endforelse

    </div>

</div>

@endsection