@extends('layouts.master')

@section('page-title', 'New Option')

@section('content')

<div class="container-fluid">

    <x-admin.page-header
        :title="'New Option - '.ucwords($field->label)"
        :subtitle="'Add an option for '.$service->name">

        <a
            href="{{ route(
                'services.fields.options.index',
                [$service, $field]
            ) }}"
            class="btn btn-secondary">

            <i class="bi bi-arrow-left me-1"></i>

            Back to Options

        </a>

    </x-admin.page-header>


    <x-admin.breadcrumb
        :items="[
            'Master Data',
            'Services',
            $service->name,
            'Fields',
            ucwords($field->label),
            'New Option'
        ]"
    />


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


    <div class="row justify-content-center">

        <div class="col-xl-8 col-lg-10">

            <div class="card shadow-sm">

                <div class="card-header bg-white">

                    <h5 class="mb-0">

                        <i class="bi bi-plus-circle me-2"></i>

                        Create New Option

                    </h5>

                </div>


                <div class="card-body p-4">

                    <form
                        method="POST"
                        action="{{ route(
                            'services.fields.options.store',
                            [$service, $field]
                        ) }}">

                        @csrf


                        {{-- Label --}}
                        <div class="mb-4">

                            <label
                                for="label"
                                class="form-label fw-semibold">

                                Label
                                <span class="text-danger">*</span>

                            </label>

                            <input
                                type="text"
                                id="label"
                                name="label"
                                class="form-control"
                                value="{{ old('label') }}"
                                placeholder="Example: Full Coverage"
                                required>

                            <div class="form-text">

                                The label that users will see.

                            </div>

                        </div>


                        {{-- Value --}}
                        <div class="mb-4">

                            <label
                                for="value"
                                class="form-label fw-semibold">

                                Value
                                <span class="text-danger">*</span>

                            </label>

                            <input
                                type="text"
                                id="value"
                                name="value"
                                class="form-control"
                                value="{{ old('value') }}"
                                placeholder="Example: full_coverage"
                                required>

                            <div class="form-text">

                                The value stored by the system.

                            </div>

                        </div>


                        {{-- Active --}}
                        <div class="mb-4">

                            <div class="form-check">

                                <input
                                    type="checkbox"
                                    name="active"
                                    value="1"
                                    class="form-check-input"
                                    id="active"
                                    @checked(old('active', true))>

                                <label
                                    class="form-check-label fw-semibold"
                                    for="active">

                                    Active

                                </label>

                            </div>

                            <div class="form-text">

                                Active options will appear on the request form.

                            </div>

                        </div>


                        {{-- Buttons --}}
                        <div class="d-flex justify-content-end gap-2">

                            <a
                                href="{{ route(
                                    'services.fields.options.index',
                                    [$service, $field]
                                ) }}"
                                class="btn btn-secondary">

                                Cancel

                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary">

                                <i class="bi bi-check-circle me-1"></i>

                                Create Option

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection