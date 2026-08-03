@extends('layouts.master')

@section('page-title', $service->name)

@section('content')

<div class="container">

    <div class="card shadow-sm">

        <div class="card-header">

            <h3>{{ $service->name }}</h3>

            @if($service->description)
                <p class="text-muted mb-0">
                    {{ $service->description }}
                </p>
            @endif

        </div>

        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form
                method="POST"
                action="{{ route('request-wizard.store', $service) }}"
                enctype="multipart/form-data">

                @csrf

                {{-- ========================================================= --}}
                {{-- Request Information --}}
                {{-- ========================================================= --}}

                <h5 class="mb-3">Request Information</h5>

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Title <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="title"
                            value="{{ old('title') }}"
                            class="form-control @error('title') is-invalid @enderror"
                            required>

                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Subject <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="subject"
                            value="{{ old('subject') }}"
                            class="form-control @error('subject') is-invalid @enderror"
                            required>

                        @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Description
                    </label>

                    <textarea
                        name="description"
                        rows="4"
                        class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>

                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Date Received <span class="text-danger">*</span>
                        </label>

                        <input
                            type="date"
                            name="date_received"
                            value="{{ old('date_received', now()->toDateString()) }}"
                            class="form-control @error('date_received') is-invalid @enderror"
                            required>

                        @error('date_received')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Request Type
                        </label>

                        <select
                            name="request_type"
                            class="form-select @error('request_type') is-invalid @enderror">

                            <option value="Internal"
                                {{ old('request_type') == 'Internal' ? 'selected' : '' }}>
                                Internal
                            </option>

                            <option value="External"
                                {{ old('request_type') == 'External' ? 'selected' : '' }}>
                                External
                            </option>

                        </select>

                        @error('request_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Priority
                        </label>

                        <select
                            name="priority"
                            class="form-select @error('priority') is-invalid @enderror">

                            <option value="Low"
                                {{ old('priority') == 'Low' ? 'selected' : '' }}>
                                Low
                            </option>

                            <option value="Normal"
                                {{ old('priority', 'Normal') == 'Normal' ? 'selected' : '' }}>
                                Normal
                            </option>

                            <option value="High"
                                {{ old('priority') == 'High' ? 'selected' : '' }}>
                                High
                            </option>

                            <option value="Urgent"
                                {{ old('priority') == 'Urgent' ? 'selected' : '' }}>
                                Urgent
                            </option>

                        </select>

                        @error('priority')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

                <hr>

                {{-- ========================================================= --}}
                {{-- Dynamic Service Fields --}}
                {{-- ========================================================= --}}

                <h5 class="mb-3">
                    Service Information
                </h5>

                @foreach($service->fields as $field)

                    <x-dynamic.field
                        :field="$field"
                        :name="'answers['.$field->id.']'"
                        :value="old('answers.'.$field->id)"
                    />

                @endforeach

                <hr>

                <button
                    type="submit"
                    class="btn btn-primary">

                    <i class="bi bi-send"></i>
                    Submit Request

                </button>

            </form>

        </div>

    </div>

</div>

@endsection