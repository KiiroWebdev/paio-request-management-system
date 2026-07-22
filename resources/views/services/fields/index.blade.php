@extends('layouts.master')

@section('page-title', 'Service Fields')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <x-admin.page-header
        :title="$service->name"
        subtitle="Manage dynamic fields for this service">

        <div class="d-flex gap-2">

            <a href="{{ route('services.index') }}"
               class="btn btn-light border">

                <i class="bi bi-arrow-left"></i>

                Back

            </a>

            <a href="{{ route('services.fields.create', $service) }}"
               class="btn btn-primary">

                <i class="bi bi-plus-circle"></i>

                Add Field

            </a>

        </div>

    </x-admin.page-header>

    {{-- Breadcrumb --}}
    <x-admin.breadcrumb
        :items="[
            'Master Data',
            'Services',
            $service->name,
            'Fields'
        ]"/>

    {{-- Table --}}
    <x-admin.table-card>

        <table class="table table-hover align-middle">

            <thead class="table-light">

                <tr>

                    <th width="80">Order</th>

                    <th>Label</th>

                    <th>Field Name</th>

                    <th width="170">Field Type</th>

                    <th width="100">Required</th>

                    <th width="100">Status</th>

                    <th width="180" class="text-center">

                        Actions

                    </th>

                </tr>

            </thead>

            <tbody>

            @forelse($service->fields as $field)

                <tr>

                    <td>

                        {{ $field->display_order }}

                    </td>

                    <td>

                        <strong>

                            {{ $field->label }}

                        </strong>

                    </td>

                    <td>

                        <code>

                            {{ $field->field_name }}

                        </code>

                    </td>

                    <td>

                        <i class="bi {{ $field->fieldType->icon }}"></i>

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

                        <x-admin.status-badge
                            :active="$field->active"/>

                    </td>

                    <td class="text-center">

                        <div class="btn-group">

                            {{-- Options --}}
                            @if($field->fieldType->name === 'Select')

                                <a href="#"
                                   class="btn btn-sm btn-outline-info"
                                   title="Manage Options">

                                    <i class="bi bi-list-ul"></i>

                                </a>

                            @endif

                            {{-- Edit --}}
                            <a href="{{ route('services.fields.edit', [$service, $field]) }}"
                               class="btn btn-sm btn-outline-primary">

                                <i class="bi bi-pencil"></i>

                            </a>

                            {{-- Delete --}}
                            <button
                                class="btn btn-sm btn-outline-danger">

                                <i class="bi bi-trash"></i>

                            </button>

                        </div>

                    </td>

                </tr>

            @empty

                <x-admin.empty-state
                    colspan="7"
                    icon="bi-ui-checks-grid"
                    title="No Fields Found"
                    message="This service has no configured fields yet."
                    buttonText="Add Field"
                    :buttonRoute="route('services.fields.create', $service)" />

            @endforelse

            </tbody>

        </table>

    </x-admin.table-card>

</div>

@endsection