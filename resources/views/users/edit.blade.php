@extends('layouts.master')

@section('page-title', 'Edit User')

@section('content')

<div class="container-fluid">

    <div class="mb-4">
        <h2 class="fw-bold mb-1">Edit User</h2>
        <p class="text-muted mb-0">
            Update the user's account information and role.
        </p>
    </div>

    <div class="card shadow-sm border-0">

        <div class="card-header">
            <strong>
                <i class="bi bi-person-gear me-1"></i>
                User Information
            </strong>
        </div>

        <div class="card-body">

            <form
                method="POST"
                action="{{ route('users.update', $user) }}"
            >

                @csrf
                @method('PUT')

                <div class="row">

                    {{-- Name --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-semibold">
                            Name
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}"
                            required
                        >

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Email --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-semibold">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}"
                            required
                        >

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Role --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-semibold">
                            Role
                        </label>

                        <select
                            name="role"
                            class="form-select @error('role') is-invalid @enderror"
                            required
                        >

                            <option
                                value="Staff"
                                @selected(old('role', $user->role) === 'Staff')
                            >
                                Staff
                            </option>

                            <option
                                value="Admin"
                                @selected(old('role', $user->role) === 'Admin')
                            >
                                Admin
                            </option>

                        </select>

                        @error('role')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        @if($user->id === auth()->id())
                            <small class="text-muted">
                                Your own account must remain an Admin.
                            </small>
                        @endif

                    </div>

                    {{-- Password --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-semibold">
                            New Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            class="form-control @error('password') is-invalid @enderror"
                        >

                        <small class="text-muted">
                            Leave blank to keep the current password.
                        </small>

                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Confirm Password --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-semibold">
                            Confirm New Password
                        </label>

                        <input
                            type="password"
                            name="password_confirmation"
                            class="form-control"
                        >

                    </div>

                </div>

                <hr>

                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="{{ route('users.index') }}"
                        class="btn btn-secondary"
                    >
                        <i class="bi bi-arrow-left me-1"></i>
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-check-circle me-1"></i>
                        Save Changes
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection