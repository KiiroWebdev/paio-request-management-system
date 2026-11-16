@extends('layouts.master')

@section('title', 'Edit Archived Document')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Edit Archived Document</h3>
            <p class="text-muted mb-0">Update the document information or replace its PDF file.</p>
        </div>

        <a href="{{ route('document-archives.show', $archivedDocument) }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Details
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <form action="{{ route('document-archives.update', $archivedDocument) }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf
                @method('PUT')

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
                                    @selected(old('document_type', $archivedDocument->document_type) === $type)>
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
                               value="{{ old('document_number', $archivedDocument->document_number) }}"
                               placeholder="Enter document number">

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
                               value="{{ old('subject', $archivedDocument->subject) }}"
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
                               value="{{ old('document_date', $archivedDocument->document_date?->format('Y-m-d')) }}">

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
                               value="{{ old('originating_office', $archivedDocument->originating_office) }}">

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
                               value="{{ old('recipient', $archivedDocument->recipient) }}">

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
                                  class="form-control @error('remarks') is-invalid @enderror">{{ old('remarks', $archivedDocument->remarks) }}</textarea>

                        @error('remarks')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <div class="border rounded p-3 bg-light">
                            <label class="form-label fw-semibold">Current PDF File</label>

                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-file-earmark-pdf text-danger fs-3"></i>

                                <div>
                                    <div class="fw-semibold">
                                        {{ $archivedDocument->original_file_name }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $archivedDocument->file_size
                                            ? number_format($archivedDocument->file_size / 1048576, 2) . ' MB'
                                            : 'File size unavailable' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="file" class="form-label fw-semibold">
                            Replace PDF File (Optional)
                        </label>

                        <input type="file"
                               name="file"
                               id="file"
                               accept=".pdf,application/pdf"
                               class="form-control @error('file') is-invalid @enderror">

                        <div class="form-text">
                            Leave this blank to keep the existing PDF. PDF only, maximum 20 MB.
                        </div>

                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('document-archives.show', $archivedDocument) }}"
                       class="btn btn-light">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>
                        Save Changes
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection