@extends('layouts.master')

@section('title', 'Upload Document')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Upload Document</h3>
            <p class="text-muted mb-0">Add a document to the General Document Archives.</p>
        </div>

        <a href="{{ route('document-archives.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Archives
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <form action="{{ route('document-archives.store') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                <div class="row g-3">

                    <div class="col-md-6">
                        <label for="document_type" class="form-label fw-semibold">
                            Document Category <span class="text-danger">*</span>
                        </label>

                        <select name="document_type"
                                id="document_type"
                                class="form-select @error('document_type') is-invalid @enderror"
                                required>
                            <option value="">Select document category</option>

                            @foreach($documentTypes as $type)
    <option value="{{ $type }}"
        @selected(old('document_type') === $type)>
        {{ $type }}
    </option>
@endforeach
                        </select>

                        @error('document_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="document_number" class="form-label fw-semibold">
                            Document Number
                        </label>

                        <input type="text"
                               name="document_number"
                               id="document_number"
                               class="form-control @error('document_number') is-invalid @enderror"
                               value="{{ old('document_number') }}"
                               placeholder="e.g. Memorandum No. 001-2026">

                        @error('document_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="subject" class="form-label fw-semibold">
                            Subject / Title <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="subject"
                               id="subject"
                               class="form-control @error('subject') is-invalid @enderror"
                               value="{{ old('subject') }}"
                               placeholder="Enter document subject or title"
                               required>

                        @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="document_date" class="form-label fw-semibold">
                            Document Date
                        </label>

                        <input type="date"
                               name="document_date"
                               id="document_date"
                               class="form-control @error('document_date') is-invalid @enderror"
                               value="{{ old('document_date') }}">

                        @error('document_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="originating_office" class="form-label fw-semibold">
                            Originating Office
                        </label>

                        <input type="text"
                               name="originating_office"
                               id="originating_office"
                               class="form-control @error('originating_office') is-invalid @enderror"
                               value="{{ old('originating_office') }}"
                               placeholder="e.g. Office of the City Mayor">

                        @error('originating_office')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="recipient" class="form-label fw-semibold">
                            Recipient
                        </label>

                        <input type="text"
                               name="recipient"
                               id="recipient"
                               class="form-control @error('recipient') is-invalid @enderror"
                               value="{{ old('recipient') }}"
                               placeholder="Enter recipient">
                        
                        @error('recipient')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="remarks" class="form-label fw-semibold">
                            Remarks
                        </label>

                        <textarea name="remarks"
                                  id="remarks"
                                  rows="3"
                                  class="form-control @error('remarks') is-invalid @enderror"
                                  placeholder="Additional notes (optional)">{{ old('remarks') }}</textarea>

                        @error('remarks')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="file" class="form-label fw-semibold">
                            PDF Document <span class="text-danger">*</span>
                        </label>

                        <input type="file"
                               name="file"
                               id="file"
                               accept=".pdf,application/pdf"
                               class="form-control @error('file') is-invalid @enderror"
                               required>

                        <div class="form-text">
                            PDF files only. Maximum file size: 20 MB.
                        </div>

                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('document-archives.index') }}"
                       class="btn btn-light">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-cloud-arrow-up me-1"></i>
                        Upload Document
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection