@extends('layouts.master')
@section('page-title', 'Services')
@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <x-admin.page-header
        title="Service Management"
        subtitle="Manage all PAIO services">

        <a href="{{ route('services.create') }}"
           class="btn btn-primary">

            <i class="bi bi-plus-circle"></i>

            New Service

        </a>

    </x-admin.page-header>
    <x-admin.breadcrumb
        :items="['Master Data', 'Services']"/>

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

    {{-- Statistics --}}
    <div class="row mb-4">

        <div class="col-lg-4 mb-3">

            <x-admin.stat-card
                title="Total Services"
                :value="$totalServices"
                icon="bi-grid-3x3-gap-fill"
                color="primary"/>

        </div>

        <div class="col-lg-4 mb-3">

            <x-admin.stat-card
                title="Active Services"
                :value="$activeServices"
                icon="bi-check-circle-fill"
                color="success"/>

        </div>

        <div class="col-lg-4 mb-3">

            <x-admin.stat-card
                title="Inactive Services"
                :value="$inactiveServices"
                icon="bi-pause-circle-fill"
                color="secondary"/>

        </div>

    </div>

    {{-- Search --}}
    <x-admin.search-bar
    placeholder="Search services..." />

    {{-- Services Table --}}
    <x-admin.table-card>

        <table class="table table-hover align-middle">

            <thead class="table-light">

                <tr>

                    <th width="80">Order</th>

                    <th>Service</th>

                    <th>Description</th>

                    <th width="120">Status</th>

                    <th width="170" class="text-center">

                        Actions

                    </th>

                </tr>

            </thead>

            <tbody>

            @forelse($services as $service)

                <tr>

                    <td>

                        {{ $service->sort_order }}

                    </td>

                    <td>

                        <strong>

                            {{ $service->name }}

                        </strong>

                    </td>

                    <td>

                        {{ $service->description }}

                    </td>

                    <td>

                        <x-admin.status-badge
                            :active="$service->is_active"/>

                    </td>

                    <td class="text-center">

    <div class="d-flex justify-content-center align-items-center gap-2">

        <a href="{{ route('services.fields.index', $service) }}"
           class="btn btn-sm btn-outline-primary"
           title="Manage Fields">

            <i class="bi bi-ui-checks-grid"></i>

        </a>

        <x-admin.action-buttons
            :edit="route('services.edit', $service)"
            :delete="route('services.destroy', $service)"
            :modalId="'deleteService'.$service->id">

            Are you sure you want to delete

            <strong>{{ $service->name }}</strong>?

            <br><br>

            This action cannot be undone.

        </x-admin.action-buttons>

    </div>

</td>

                </tr>

            @empty

              
<x-admin.empty-state
    colspan="5"
    icon="bi-grid"
    title="No Services Found"
    message="Click the button below to create your first service."
    buttonText="New Service"
    :buttonRoute="route('services.create')" />


            @endforelse

            </tbody>

        </table>

    </x-admin.table-card>

    {{-- Pagination --}}
    <div class="mt-3">

        {{ $services->links() }}

    </div>

</div>

@endsection