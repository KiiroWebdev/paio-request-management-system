@extends('layouts.master')

@section('title', 'Deleted Documents')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">
                <i class="bi bi-trash3 me-2"></i>Deleted Documents
            </h3>
            <p class="text-muted mb-0">
                View and restore previously deleted archived documents.
            </p>
        </div>

        <a href="{{ route('document-archives.index') }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Back to Archives
        </a>
    </div>

    {{-- Notice --}}
    <div class="alert alert-warning">
        <i class="bi bi-info-circle me-2"></i>
        Documents in this section are soft-deleted. Their PDF files are retained
        until the documents are restored or otherwise handled.
    </div>

    {{-- Deleted Documents Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">Recycle Bin</h5>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Document</th>
                            <th>Category</th>
                            <th>Document Date</th>
                            <th>Uploaded By</th>
                            <th>Deleted At</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($documents as $document)
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-semibold">
                                        {{ $document->subject }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $document->document_number ?: 'No document number' }}
                                    </small>
                                </td>

                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $document->document_type }}
                                    </span>
                                </td>

                                <td>
                                    {{ $document->document_date?->format('M d, Y') ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $document->uploadedBy?->name ?? 'Unknown' }}
                                </td>

                                <td>
                                    {{ $document->deleted_at?->format('M d, Y h:i A') }}
                                </td>

                                <td class="text-center">
                                    <form action="{{ route('document-archives.restore', $document->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Restore this document to General Document Archives?');">

                                        @csrf

                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="bi bi-arrow-counterclockwise me-1"></i>
                                            Restore
                                        </button>

                                    </form>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="bi bi-trash3 text-muted fs-1"></i>
                                    <h6 class="fw-semibold mt-3">Recycle Bin is Empty</h6>
                                    <p class="text-muted mb-0">
                                        There are currently no deleted documents.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>

        @if($documents->hasPages())
            <div class="card-footer bg-white">
                {{ $documents->links() }}
            </div>
        @endif

    </div>

</div>
@endsection
