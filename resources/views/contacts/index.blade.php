@extends('layouts.master')

@section('page-title', 'Contacts')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <x-admin.page-header
        title="Contact Management"
        subtitle="Manage organization contacts">

        <a href="{{ route('contacts.create') }}"
           class="btn btn-primary">

            <i class="bi bi-plus-circle"></i>

            New Contact

        </a>

    </x-admin.page-header>

    {{-- Breadcrumb --}}
    <x-admin.breadcrumb
        :items="['Master Data', 'Contacts']"/>

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

        <div class="col-lg-3 mb-3">
            <x-admin.stat-card
                title="Total Contacts"
                :value="$totalContacts"
                icon="bi-people-fill"
                color="primary"/>
        </div>

        <div class="col-lg-3 mb-3">
            <x-admin.stat-card
                title="Primary Contacts"
                :value="$primaryContacts"
                icon="bi-star-fill"
                color="warning"/>
        </div>

        <div class="col-lg-3 mb-3">
            <x-admin.stat-card
                title="Internal"
                :value="$internalContacts"
                icon="bi-building-fill"
                color="success"/>
        </div>

        <div class="col-lg-3 mb-3">
            <x-admin.stat-card
                title="External"
                :value="$externalContacts"
                icon="bi-globe"
                color="info"/>
        </div>

    </div>

    {{-- Search --}}
    <x-admin.search-bar
        placeholder="Search contacts..."
        :value="request('search')" />

    {{-- Contacts Table --}}
    <x-admin.table-card>

        <table class="table table-hover align-middle">

            <thead class="table-light">

                <tr>

                    <th>Name</th>
                    <th>Organization</th>
                    <th>Position</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>Primary</th>
                    <th>Status</th>
                    <th class="text-center" width="170">

                        Actions

                    </th>

                </tr>

            </thead>

            <tbody>

            @forelse($contacts as $contact)

                <tr>

                    <td>

                        <strong>{{ $contact->full_name }}</strong>

                    </td>

                    <td>

                        {{ $contact->organization->name }}

                    </td>

                    <td>

                        {{ $contact->position }}

                    </td>

                    <td>

                        {{ $contact->mobile ?? '-' }}

                    </td>

                    <td>

                        {{ $contact->email ?? '-' }}

                    </td>

                    <td>

                        @if($contact->is_primary)

                            <span class="badge bg-warning text-dark">

                                Primary

                            </span>

                        @else

                            -

                        @endif

                    </td>

                    <td>

                        <x-admin.status-badge
                            :active="$contact->is_active"/>

                    </td>

                    <td class="text-center">

                        <x-admin.action-buttons
                            :edit="route('contacts.edit', $contact)"
                            :delete="route('contacts.destroy', $contact)"
                            :modalId="'deleteContact'.$contact->id">

                            Are you sure you want to delete

                            <strong>{{ $contact->full_name }}</strong>?

                            <br><br>

                            This action cannot be undone.

                        </x-admin.action-buttons>

                    </td>

                </tr>

            @empty

                <x-admin.empty-state
                    colspan="8"
                    icon="bi-person-lines-fill"
                    title="No Contacts Found"
                    message="Click below to create your first contact."
                    buttonText="New Contact"
                    :buttonRoute="route('contacts.create')" />

            @endforelse

            </tbody>

        </table>

    </x-admin.table-card>

    <div class="mt-3">

        {{ $contacts->links() }}

    </div>

</div>

@endsection