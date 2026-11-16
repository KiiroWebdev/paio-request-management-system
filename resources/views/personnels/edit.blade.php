@extends('layouts.master')

@section('page-title', 'Edit Personnel')

@section('content')
<div class="container-fluid">

    <x-admin.page-header
        title="Edit Personnel"
        subtitle="Update personnel information and assignment availability">

        <a href="{{ route('personnels.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Back to Personnel
        </a>
    </x-admin.page-header>

    <x-admin.breadcrumb :items="['Administration', 'Personnel Directory', 'Edit Personnel']" />

    <div class="row">
        <div class="col-xl-8 col-lg-10">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                    <form action="{{ route('personnels.update', $personnel) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Full Name <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $personnel->name) }}"
                                maxlength="255"
                                required
                                autofocus
                            >

                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="position" class="form-label">Position</label>

                            <input
                                type="text"
                                id="position"
                                name="position"
                                class="form-control @error('position') is-invalid @enderror"
                                value="{{ old('position', $personnel->position) }}"
                                maxlength="255"
                            >

                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="department" class="form-label">Department / Office</label>

                            <input
                                type="text"
                                id="department"
                                name="department"
                                class="form-control @error('department') is-invalid @enderror"
                                value="{{ old('department', $personnel->department) }}"
                                maxlength="255"
                            >

                            @error('department')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        
<div class="mb-4">
    <label for="user_id" class="form-label">
        Linked System Staff Account
    </label>

    <select
        name="user_id"
        id="user_id"
        class="form-select @error('user_id') is-invalid @enderror"
    >
        <option value="">No linked account</option>

        @forelse ($staffUsers as $staffUser)
            <option
                value="{{ $staffUser->id }}"
                @selected(old('user_id', $personnel->user_id) == $staffUser->id)
            >
                {{ $staffUser->name }} ({{ $staffUser->email }})
            </option>
        @empty
            <option value="" disabled>
                No available active Staff accounts
            </option>
        @endforelse
    </select>

    @error('user_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    <div class="form-text">
        Select an existing active Staff account or choose No linked account.
        You can change or remove this link at any time.
    </div>
</div>


                        <div class="form-check form-switch mb-4">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                role="switch"
                                id="is_active"
                                name="is_active"
                                value="1"
                                @checked(old('is_active', $personnel->is_active ? '1' : '0') == '1')
                            >

                            <label class="form-check-label" for="is_active">
                                Active — available for new assignments
                            </label>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('personnels.index') }}"
                               class="btn btn-light border">
                                Cancel
                            </a>

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>
                                Update Personnel
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

</div>
@endsection