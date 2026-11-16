@extends('layouts.master')

@section('page-title', 'Personnel Directory')

@section('content')
<div class="container-fluid">

    <x-admin.page-header
        title="Personnel Directory"
        subtitle="Manage personnel available for document request assignments">

        <a href="{{ route('personnels.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus me-1"></i>
            Add Personnel
        </a>
    </x-admin.page-header>

    <x-admin.breadcrumb :items="['Administration', 'Personnel Directory']" />

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($personnels as $personnel)
                            <tr>
                                <td class="fw-semibold">
                                    {{ $personnel->name }}
                                </td>

                                <td>{{ $personnel->position ?: '—' }}</td>

                                <td>{{ $personnel->department ?: '—' }}</td>

                                <td>
                                    @if($personnel->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex justify-content-end gap-2">

                                        <a href="{{ route('personnels.edit', $personnel) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="Edit personnel">
                                            <i class="bi bi-pencil-square"></i>
                                            Edit
                                        </a>

                                        <form action="{{ route('personnels.toggle-status', $personnel) }}"
                                              method="POST">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    class="btn btn-sm {{ $personnel->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                                <i class="bi {{ $personnel->is_active ? 'bi-person-dash' : 'bi-person-check' }} me-1"></i>
                                                {{ $personnel->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>

                                        <form action="{{ route('personnels.destroy', $personnel) }}"
                                              method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this personnel record?');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="bi bi-people fs-2 d-block mb-2"></i>
                                    No personnel records found.
                                    <div class="mt-2">
                                        Add personnel to make them available for assignments.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $personnels->links() }}
            </div>

        </div>
    </div>

</div>
@endsection