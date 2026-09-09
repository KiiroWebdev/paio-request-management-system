@extends('layouts.master')

@section('page-title', 'Add User')

@section('content')

<div class="container-fluid">

    <div class="mb-4">
        <h2 class="fw-bold mb-1">Add User</h2>
        <p class="text-muted mb-0">
            Create a new system user.
        </p>
    </div>

    <div class="card shadow-sm border-0">

        <div class="card-header">
            <strong>
                <i class="bi bi-person-plus me-1"></i>
                User Information
            </strong>
        </div>

        <div class="card-body">

            <form method="POST" action="{{ route('users.store') }}">

                @csrf

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
                            value="{{ old('name') }}"
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
                            value="{{ old('email') }}"
                            required
                        >

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Password --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-semibold">
                            Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            required
                        >

                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Confirm Password --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-semibold">
                            Confirm Password
                        </label>

                        <input
                            type="password"
                            name="password_confirmation"
                            class="form-control"
                            required
                        >

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

                            <option value="Staff" @selected(old('role', 'Staff') === 'Staff')>
                                Staff
                            </option>

                            <option value="Admin" @selected(old('role') === 'Admin')>
                                Admin
                            </option>

                        </select>

                        @error('role')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <small class="text-muted">
                            Staff is recommended for regular system users.
                        </small>

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
                        <i class="bi bi-person-plus me-1"></i>
                        Create User
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection