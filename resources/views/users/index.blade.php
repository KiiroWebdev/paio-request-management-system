@extends('layouts.master')

@section('page-title', 'User Management')

@section('content')

@if(session('success'))

    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">

        <i class="bi bi-check-circle-fill me-2"></i>

        {{ session('success') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Close">
        </button>

    </div>

@endif

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">User Management</h2>
            <p class="text-muted mb-0">
                Manage system users and their roles.
            </p>
        </div>

        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus me-1"></i>
            Add User
        </a>

    </div>

    <div class="card shadow-sm border-0">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>
                        <tr>
                            <th class="px-4">Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Created</th>
                            <th>Status</th>
                            <th width="160" class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($users as $user)

                            <tr>

                                <td class="px-4 fw-semibold">
                                    {{ $user->name }}
                                </td>

                                <td>
                                    {{ $user->email }}
                                </td>

                                <td>

                                    @if($user->isAdmin())

                                        <span class="badge bg-danger">
                                            <i class="bi bi-shield-fill me-1"></i>
                                            Admin
                                        </span>

                                    @else

                                        <span class="badge bg-primary">
                                            <i class="bi bi-person me-1"></i>
                                            Staff
                                        </span>

                                    @endif

                                </td>
                                {{-- Status --}}
<td>

    @if($user->is_active)

        <span class="badge bg-success">
            <i class="bi bi-check-circle me-1"></i>
            Active
        </span>

    @else

        <span class="badge bg-secondary">
            <i class="bi bi-x-circle me-1"></i>
            Inactive
        </span>

    @endif

</td>
                                <td>
                                    {{ $user->created_at?->format('M d, Y') }}
                                </td>

                                <td class="text-center">

    {{-- Edit --}}
    <a
        href="{{ route('users.edit', $user) }}"
        class="btn btn-sm btn-outline-primary"
        title="Edit User"
    >
        <i class="bi bi-pencil"></i>
    </a>

    {{-- Activate / Deactivate --}}
    @if($user->id !== auth()->id())

        <form
            method="POST"
            action="{{ route('users.toggle-status', $user) }}"
            class="d-inline"
            onsubmit="return confirm('{{ $user->is_active ? 'Deactivate this user?' : 'Activate this user?' }}')"
        >

            @csrf
            @method('PATCH')

            @if($user->is_active)

                <button
                    type="submit"
                    class="btn btn-sm btn-outline-danger"
                    title="Deactivate User"
                >
                    <i class="bi bi-person-x"></i>
                </button>

            @else

                <button
                    type="submit"
                    class="btn btn-sm btn-outline-success"
                    title="Activate User"
                >
                    <i class="bi bi-person-check"></i>
                </button>

            @endif

        </form>

    @else

        <button
            type="button"
            class="btn btn-sm btn-outline-secondary"
            disabled
            title="You cannot deactivate your own account"
        >
            <i class="bi bi-shield-lock"></i>
        </button>

    @endif

</td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    No users found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <div class="mt-3">
        {{ $users->links() }}
    </div>

</div>

@endsection