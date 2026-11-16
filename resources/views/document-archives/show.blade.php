
@extends('layouts.master')

@section('title', 'Document Details')

@section('content')
<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Document Details</h3>
            <p class="text-muted mb-0">View archived document information and file history.</p>
        </div>

        <a href="{{ route('document-archives.index') }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Archives
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- Document Information --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <span class="badge bg-primary mb-2">
                                {{ $archivedDocument->document_type }}
                            </span>

                            <h4 class="fw-bold mb-1">
                                {{ $archivedDocument->subject }}
                            </h4>

                            <p class="text-muted mb-0">
                                {{ $archivedDocument->document_number ?: 'No document number assigned' }}
                            </p>
                        </div>

                        <i class="bi bi-file-earmark-pdf text-danger fs-1"></i>
                    </div>

                    <hr>

                    <div class="row g-4">

                        <div class="col-md-6">
                            <small class="text-muted d-block">Document Date</small>
                            <strong>
                                {{ $archivedDocument->document_date?->format('F d, Y') ?? 'Not specified' }}
                            </strong>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block">Originating Office</small>
                            <strong>
                                {{ $archivedDocument->originating_office ?: 'Not specified' }}
                            </strong>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block">Recipient</small>
                            <strong>
                                {{ $archivedDocument->recipient ?: 'Not specified' }}
                            </strong>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block">Uploaded By</small>
                            <strong>
                                {{ $archivedDocument->uploadedBy?->name ?? 'Unknown' }}
                            </strong>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block">Date Uploaded</small>
                            <strong>
                                {{ $archivedDocument->created_at?->format('F d, Y h:i A') }}
                            </strong>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block">Last Updated</small>
                            <strong>
                                {{ $archivedDocument->updated_at?->format('F d, Y h:i A') }}
                            </strong>
                        </div>

                        <div class="col-12">
                            <small class="text-muted d-block">Remarks</small>
                            <p class="mb-0">
                                {{ $archivedDocument->remarks ?: 'No remarks provided.' }}
                            </p>
                        </div>

                    </div>

                    <hr>

                    {{-- PDF Information --}}
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-file-earmark-pdf text-danger fs-2"></i>

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

                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('document-archives.preview', $archivedDocument) }}"
                               target="_blank"
                               rel="noopener"
                               class="btn btn-outline-danger">
                                <i class="bi bi-eye me-1"></i> Preview PDF
                            </a>

                            <a href="{{ route('document-archives.download', $archivedDocument) }}"
                               class="btn btn-primary">
                                <i class="bi bi-download me-1"></i> Download
                            </a>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('document-archives.edit', $archivedDocument) }}"
                           class="btn btn-warning">
                            <i class="bi bi-pencil-square me-1"></i> Edit Document
                        </a>

                        @if(auth()->user()->isAdmin())
                            <form action="{{ route('document-archives.destroy', $archivedDocument) }}"
                                  method="POST"
                                  onsubmit="return confirm('Are you sure you want to move this document to deleted archives?');">

                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="bi bi-trash me-1"></i> Delete
                                </button>
                            </form>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        {{-- Activity History --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-clock-history me-2"></i>
                        Activity History
                    </h5>
                </div>

                <div class="card-body">

                    @forelse($archivedDocument->activities->sortByDesc('created_at') as $activity)
                        <div class="d-flex gap-3 mb-3">

                            <div class="text-primary">
                                <i class="bi bi-circle-fill"></i>
                            </div>

                            <div class="flex-grow-1">
                                <div class="fw-semibold">
                                    {{ $activity->action }}
                                </div>

                                <small class="text-muted d-block">
                                    {{ $activity->user?->name ?? 'Unknown user' }}
                                </small>

                                @if($activity->description)
                                    <small class="d-block mt-1">
                                        {{ $activity->description }}
                                    </small>
                                @endif

                                <small class="text-muted">
                                    {{ $activity->created_at?->format('M d, Y h:i A') }}
                                </small>
                            </div>

                        </div>
                    @empty
                        <p class="text-muted mb-0">
                            No activity history available.
                        </p>
                    @endforelse

                </div>
            </div>
        </div>

    </div>

</div>
@endsection
