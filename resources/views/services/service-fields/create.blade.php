@extends('layouts.master')

@section('page-title', 'Create Service Field')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h2 class="fw-bold">
            Create Service Field
        </h2>

        <p class="text-muted mb-0">
            Service:
            <strong>{{ $service->name }}</strong>
        </p>

    </div>

    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>Please fix the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <form
        method="POST"
        action="{{ route('services.fields.store', $service) }}">

        @csrf

        @include('service-fields.form')

        <div class="mt-3">

            <button
                type="submit"
                class="btn btn-primary">

                <i class="bi bi-check-circle"></i>

                Save Field

            </button>

            <a
                href="{{ route('services.fields.index', $service) }}"
                class="btn btn-secondary">

                Cancel

            </a>

        </div>

    </form>

</div>

@endsection