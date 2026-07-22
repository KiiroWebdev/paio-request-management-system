@extends('layouts.master')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <x-admin.page-header
        title="Organization Management"
        subtitle="Manage internal and external organizations">

        <a href="{{ route('organizations.create') }}"
           class="btn btn-primary">

            <i class="bi bi-plus-circle me-1"></i>

            New Organization

        </a>

    </x-admin.page-header>

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

        <div class="col-lg-4 col-md-6 mb-3">

            <x-admin.stat-card
                title="Total Organizations"
                :value="$totalOrganizations"
                icon="bi-buildings-fill"
                color="primary"/>

        </div>

        <div class="col-lg-4 col-md-6 mb-3">

            <x-admin.stat-card
                title="Internal Organizations"
                :value="$internalOrganizations"
                icon="bi-building-fill"
                color="success"/>

        </div>

        <div class="col-lg-4 col-md-6 mb-3">

            <x-admin.stat-card
                title="External Organizations"
                :value="$externalOrganizations"
                icon="bi-globe"
                color="info"/>

        </div>

    </div>

    {{-- Search & Filters --}}
    <form method="GET" class="mb-4">

        <div class="row g-3 align-items-end">

            <div class="col-lg-4">

                <label class="form-label">

                    Search

                </label>

                <div class="input-group">

                    <span class="input-group-text">

                        <i class="bi bi-search"></i>

                    </span>

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Search organizations..."
                        value="{{ request('search') }}">

                </div>

            </div>

            <div class="col-lg-3">

                <label class="form-label">

                    Organization Type

                </label>

                <select
                    name="type"
                    class="form-select">

                    <option value="">All Types</option>

                    <option value="Department" @selected(request('type')=='Department')>Department</option>

                    <option value="Office" @selected(request('type')=='Office')>Office</option>

                    <option value="Division" @selected(request('type')=='Division')>Division</option>

                    <option value="Section" @selected(request('type')=='Section')>Section</option>

                    <option value="Committee" @selected(request('type')=='Committee')>Committee</option>

                    <option value="Barangay" @selected(request('type')=='Barangay')>Barangay</option>

                    <option value="National Agency" @selected(request('type')=='National Agency')>National Agency</option>

                    <option value="GOCC" @selected(request('type')=='GOCC')>GOCC</option>

                    <option value="School" @selected(request('type')=='School')>School</option>

                    <option value="University" @selected(request('type')=='University')>University</option>

                    <option value="Hospital" @selected(request('type')=='Hospital')>Hospital</option>

                    <option value="Private Company" @selected(request('type')=='Private Company')>Private Company</option>

                    <option value="NGO" @selected(request('type')=='NGO')>NGO</option>

                    <option value="People's Organization"
                        @selected(request('type')=="People's Organization")>

                        People's Organization

                    </option>

                    <option value="Media" @selected(request('type')=='Media')>Media</option>

                    <option value="Individual" @selected(request('type')=='Individual')>Individual</option>

                    <option value="Other" @selected(request('type')=='Other')>Other</option>

                </select>

            </div>

            <div class="col-lg-2">

                <label class="form-label">

                    Category

                </label>

                <select
                    name="internal"
                    class="form-select">

                    <option value="">All</option>

                    <option value="1"
                        @selected(request('internal')==='1')>

                        Internal

                    </option>

                    <option value="0"
                        @selected(request('internal')==='0')>

                        External

                    </option>

                </select>

            </div>

            <div class="col-lg-3">

                <div class="d-flex gap-2">

                    <button
                        class="btn btn-primary flex-fill">

                        <i class="bi bi-search me-1"></i>

                        Search

                    </button>

                    <a
                        href="{{ route('organizations.index') }}"
                        class="btn btn-outline-secondary">

                        Reset

                    </a>

                </div>

            </div>

        </div>

    </form>

    {{-- Organizations Table --}}
    <x-admin.table-card>

        <table class="table table-hover align-middle">

            <thead class="table-light">

                <tr>

                    <th>Name</th>

                    <th>Short Name</th>

                    <th>Type</th>

                    <th>Email</th>

                    <th>Category</th>

                    <th>Status</th>

                    <th width="170" class="text-center">

                        Actions

                    </th>

                </tr>

            </thead>

            <tbody>

            @forelse($organizations as $organization)

                <tr>

                    <td>

                        <strong>

                            {{ $organization->name }}

                        </strong>

                    </td>

                    <td>

                        {{ $organization->short_name ?: '-' }}

                    </td>

                    <td>

                        {{ $organization->type }}

                    </td>

                    <td>

                        {{ $organization->email ?: '-' }}

                    </td>

                    <td>

                        @if($organization->is_internal)

                            <span class="badge rounded-pill bg-primary">

                                Internal

                            </span>

                        @else

                            <span class="badge rounded-pill bg-info text-dark">

                                External

                            </span>

                        @endif

                    </td>

                    <td>

                        <x-admin.status-badge
                            :active="$organization->is_active"/>

                    </td>

                    <td class="text-center">

                        <x-admin.action-buttons
                            :edit="route('organizations.edit', $organization)"
                            :delete="route('organizations.destroy', $organization)"
                            :modalId="'deleteOrganization'.$organization->id">

                            Are you sure you want to delete

                            <strong>{{ $organization->name }}</strong>?

                            <br><br>

                            This action cannot be undone.

                        </x-admin.action-buttons>

                    </td>

                </tr>

            @empty

                <x-admin.empty-state
                    colspan="7"
                    icon="bi-buildings"
                    title="No Organizations Found"
                    message="Click the button below to create your first organization."
                    buttonText="New Organization"
                    :buttonRoute="route('organizations.create')" />

            @endforelse

            </tbody>

        </table>

    </x-admin.table-card>

    {{-- Pagination --}}
    <div class="mt-3">

        {{ $organizations->links() }}

    </div>

</div>

@endsection