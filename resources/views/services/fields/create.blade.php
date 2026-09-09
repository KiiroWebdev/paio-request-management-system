@extends('layouts.master')

@section('page-title', 'New Field')

@section('content')

<div class="container-fluid">

    <x-admin.page-header
        :title="'New Field - '.$service->name"
        subtitle="Create a dynamic field for this service">

        <a
            href="{{ route('services.fields.index', $service) }}"
            class="btn btn-secondary">

            <i class="bi bi-arrow-left me-1"></i>

            Back to Fields

        </a>

    </x-admin.page-header>


    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-semibold mb-2">

                <i class="bi bi-exclamation-triangle me-1"></i>

                Please correct the following errors:

            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route('services.fields.store', $service) }}">

        @csrf

        @include('services.fields.form')

        <div class="d-flex justify-content-end gap-2 mt-4">

            <a
                href="{{ route('services.fields.index', $service) }}"
                class="btn btn-secondary">

                Cancel

            </a>

            <button
                type="submit"
                class="btn btn-primary">

                <i class="bi bi-check-circle me-1"></i>

                Create Field

            </button>

        </div>

    </form>

</div>

@endsection