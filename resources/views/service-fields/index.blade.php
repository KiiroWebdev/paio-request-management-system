@extends('layouts.master')

@section('page-title', 'Service Field Designer')

@section('content')

<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>
    @endif

    <div class="card shadow-sm">

        <div class="card-header d-flex justify-content-between align-items-center">

            <div>

                <h2 class="mb-1 fw-bold">
                    {{ $service->name }}
                </h2>

                <small class="text-muted">
                    {{ $service->description }}
                </small>

            </div>

            <a
                href="{{ route('services.fields.create', $service) }}"
                class="btn btn-primary">

                <i class="bi bi-plus-circle"></i>

                Add Field

            </a>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th width="70">#</th>

                        <th>Label</th>

                        <th width="170">Type</th>

                        <th width="120">Required</th>

                        <th width="120">Status</th>

                        <th width="220">Actions</th>

                    </tr>

                </thead>

                <tbody>

                @forelse($service->fields as $field)

                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>

                            <strong>{{ $field->label }}</strong>

                            <br>

                            <small class="text-muted">

                                {{ $field->field_name }}

                            </small>

                        </td>

                        <td>

                            {{ $field->fieldType->name }}

                        </td>

                        <td>

                            @if($field->required)

                                <span class="badge bg-success">
                                    Required
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    Optional
                                </span>

                            @endif

                        </td>

                        <td>

                            @if($field->active)

                                <span class="badge bg-primary">
                                    Active
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    Disabled
                                </span>

                            @endif

                        </td>

                        <td>

                            @php

                                $hasOptions = in_array(

                                    strtolower($field->fieldType->name),

                                    [
                                        'dropdown',
                                        'radio',
                                        'checkbox'
                                    ]

                                );

                            @endphp

                            @if($hasOptions)

                                <a
                                    href="{{ route('services.fields.options.index', [$service, $field]) }}"
                                    class="btn btn-sm btn-outline-success"
                                    title="Manage Options">

                                    <i class="bi bi-list-ul"></i>

                                </a>

                            @endif

                            <a
                                href="{{ route('services.fields.edit', [$service, $field]) }}"
                                class="btn btn-sm btn-outline-primary"
                                title="Edit">

                                <i class="bi bi-pencil"></i>

                            </a>

                            <form
                                action="{{ route('services.fields.destroy', [$service, $field]) }}"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Delete this field?');">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-sm btn-outline-danger"
                                    title="Delete">

                                    <i class="bi bi-trash"></i>

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="6"
                            class="text-center py-5">

                            <h5>No fields found.</h5>

                            <p class="text-muted mb-0">

                                Click <strong>Add Field</strong> to create your first field.

                            </p>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection