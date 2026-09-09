@extends('layouts.master')

@section('page-title', 'Field Options')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <x-admin.page-header
        :title="'Options - '.ucwords($field->label)"
        :subtitle="'Manage the available options for '.$service->name">

        <div class="d-flex gap-2">

            <a href="{{ route('services.fields.index', $service) }}"
               class="btn btn-secondary">

                <i class="bi bi-arrow-left"></i>
                Back to Fields

            </a>

            <a href="{{ route('services.fields.options.create', [$service, $field]) }}"
               class="btn btn-primary">

                <i class="bi bi-plus-circle"></i>
                New Option

            </a>

        </div>

    </x-admin.page-header>


    {{-- Breadcrumb --}}
    <x-admin.breadcrumb
        :items="[
            'Master Data',
            'Services',
            $service->name,
            'Fields',
            ucwords($field->label),
            'Options'
        ]"
    />


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show mb-4">

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Error Message --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show mb-4">

            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Field Information --}}
    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <div class="d-flex align-items-center gap-3">

                        <div
                            class="bg-primary text-white rounded p-3">

                            <i class="bi bi-list-check fs-4"></i>

                        </div>

                        <div>

                            <h5 class="mb-1 fw-bold">

                                {{ ucwords($field->label) }}

                            </h5>

                            <div class="text-muted">

                                Field name:
                                <code>{{ $field->field_name }}</code>

                            </div>

                            <div class="text-muted">

                                Type:
                                <strong>
                                    {{ $field->fieldType->name }}
                                </strong>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-md-4 text-md-end mt-3 mt-md-0">

                    <span class="badge bg-primary fs-6 px-3 py-2">

                        {{ $field->options->count() }}

                        {{ $field->options->count() === 1 ? 'Option' : 'Options' }}

                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- Options Table --}}
    <div class="card shadow-sm">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <strong>

                    <i class="bi bi-list-ul me-2"></i>

                    Available Options

                </strong>

                <span class="text-muted small">

                    Options are displayed in this order

                </span>

            </div>

        </div>


        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th width="80">
                                Order
                            </th>

                            <th>
                                Label
                            </th>

                            <th>
                                Value
                            </th>

                            <th width="120">
                                Status
                            </th>

                            <th width="160" class="text-center">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($field->options->sortBy('display_order') as $option)

                        <tr>

                            {{-- Order --}}
                            <td>

                                <span class="badge bg-light text-dark border">

                                    {{ $option->display_order }}

                                </span>

                            </td>


                            {{-- Label --}}
                            <td>

                                <strong>

                                    {{ $option->label }}

                                </strong>

                            </td>


                            {{-- Value --}}
                            <td>

                                <code>

                                    {{ $option->value }}

                                </code>

                            </td>


                            {{-- Status --}}
                            <td>

                                @if($option->active)

                                    <span class="badge bg-success">

                                        <i class="bi bi-check-circle me-1"></i>
                                        Active

                                    </span>

                                @else

                                    <span class="badge bg-secondary">

                                        <i class="bi bi-pause-circle me-1"></i>
                                        Inactive

                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}
                            <td>

                                <div class="d-flex justify-content-center gap-2">

                                    <a
                                        href="{{ route(
                                            'services.fields.options.edit',
                                            [$service, $field, $option]
                                        ) }}"
                                        class="btn btn-sm btn-outline-success"
                                        title="Edit Option">

                                        <i class="bi bi-pencil"></i>

                                    </a>


                                    <form
                                        action="{{ route(
                                            'services.fields.options.destroy',
                                            [$service, $field, $option]
                                        ) }}"
                                        method="POST"
                                        onsubmit="return confirm('Delete this option?');">

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Delete Option">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5">

                                <x-admin.empty-state
                                    icon="bi-list-check"
                                    title="No Options Yet"
                                    message="Create the first option for this field."
                                    buttonText="New Option"
                                    :buttonRoute="route(
                                        'services.fields.options.create',
                                        [$service, $field]
                                    )"
                                />

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection