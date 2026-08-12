@extends('layouts.master')

@section('page-title', 'Service Fields')

@section('content')

<div class="container-fluid">

    <x-admin.page-header
        :title="'Fields - '.$service->name"
        subtitle="Manage the dynamic fields for this service">

        <div class="d-flex gap-2">

            <a href="{{ route('services.index') }}"
               class="btn btn-secondary">

                <i class="bi bi-arrow-left"></i>
                Back to Services

            </a>

            <a href="{{ route('services.fields.create', $service) }}"
               class="btn btn-primary">

                <i class="bi bi-plus-circle"></i>
                New Field

            </a>

        </div>

    </x-admin.page-header>

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

        <div class="card-body">

            <table class="table table-hover align-middle">

                <thead class="table-light">

                    <tr>

                        <th width="70">Order</th>

                        <th>Label</th>

                        <th>Field Name</th>

                        <th width="140">Type</th>

                        <th width="100">Required</th>

                        <th width="100">Status</th>

                        <th width="140">Options</th>

                        <th width="220" class="text-center">

                            Actions

                        </th>

                    </tr>

                </thead>

                <tbody>

                @forelse($service->fields->sortBy('display_order') as $field)

                    <tr>

                        <td>

                            {{ $field->display_order }}

                        </td>

                        <td>

                            <strong>

                                {{ ucwords($field->label) }}

                            </strong>

                        </td>

                        <td>

                            <code>

                                {{ $field->field_name }}

                            </code>

                        </td>

                        <td>

                            {{ $field->fieldType->name }}

                        </td>

                        <td>

                            @if($field->required)

                                <span class="badge bg-danger">

                                    Required

                                </span>

                            @else

                                <span class="badge bg-secondary">

                                    Optional

                                </span>

                            @endif

                        </td>

                        <td>

                            <x-admin.status-badge
                                :active="$field->active" />

                        </td>

                        <td>

                            @php
                                $supportsOptions = in_array(
                                    $field->fieldType->key,
                                    ['checkbox', 'dropdown', 'radio']
                                );
                            @endphp

                            @if($supportsOptions)

                                <a href="{{ route('services.fields.options.index', [$service, $field]) }}"
                                   class="btn btn-sm btn-outline-primary">

                                    Manage
                                    ({{ $field->options_count }})

                                </a>

                            @else

                                <span class="text-muted">

                                    —

                                </span>

                            @endif

                        </td>

                        <td>

                            <div class="d-flex justify-content-center gap-2">

                                <a href="{{ route('services.fields.edit', [$service, $field]) }}"
                                   class="btn btn-sm btn-outline-success">

                                    <i class="bi bi-pencil"></i>

                                </a>

                                <form
                                    action="{{ route('services.fields.destroy', [$service, $field]) }}"
                                    method="POST"
                                    onsubmit="return confirm('Delete this field?');">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="btn btn-sm btn-outline-danger">

                                        <i class="bi bi-trash"></i>

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="8">

                            <x-admin.empty-state
                                icon="bi-ui-checks-grid"
                                title="No Fields Yet"
                                message="Create the first dynamic field for this service."
                                buttonText="New Field"
                                :buttonRoute="route('services.fields.create', $service)" />

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection