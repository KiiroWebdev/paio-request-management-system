@extends('layouts.master')

@section('page-title', 'General Document Archives')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">

        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-archive me-2"></i>
                General Document Archives
            </h2>

            <p class="text-muted mb-0">
                Manage and preserve official office documents and communications.
            </p>
        </div>

       
<div class="d-flex gap-2">

    @if(auth()->user()->isAdmin())
        <a href="{{ route('document-archives.trashed') }}"
           class="btn btn-outline-danger">
            <i class="bi bi-trash3 me-1"></i>
            Recycle Bin
        </a>
    @endif

    <a href="{{ route('document-archives.create') }}"
       class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>
        Upload Document
    </a>

</div>


    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Search and Filters --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('document-archives.index') }}">

                <div class="row g-3 align-items-end">

                    {{-- Search --}}
                    <div class="col-lg-5">

                        <label class="form-label fw-semibold">
                            Search Documents
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search subject, document number, or office..."
                            value="{{ request('search') }}"
                        >

                    </div>

                    {{-- Category --}}
                    <div class="col-lg-3">

                        <label class="form-label fw-semibold">
                            Document Category
                        </label>

                        <select name="document_type" class="form-select">

                            <option value="">All Categories</option>

                            @foreach($documentTypes as $type)
                                <option
                                    value="{{ $type }}"
                                    @selected(request('document_type') === $type)
                                >
                                    {{ $type }}
                                </option>
                            @endforeach

                        </select>

                    </div>

                    {{-- Year --}}
                    <div class="col-lg-2">

                        <label class="form-label fw-semibold">
                            Year
                        </label>

                        <input
                            type="number"
                            name="year"
                            class="form-control"
                            placeholder="e.g. 2026"
                            min="1900"
                            max="2100"
                            value="{{ request('year') }}"
                        >

                    </div>

                    {{-- Buttons --}}
                    <div class="col-lg-2 d-flex gap-2">

                        <button type="submit"
                                class="btn btn-primary flex-fill">

                            <i class="bi bi-search"></i>
                            Search

                        </button>

                        <a href="{{ route('document-archives.index') }}"
                           class="btn btn-outline-secondary"
                           title="Clear filters">

                            <i class="bi bi-arrow-counterclockwise"></i>

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- Documents Table --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <strong>
                    <i class="bi bi-folder2-open me-2"></i>
                    Archived Documents
                </strong>

                <span class="badge bg-primary">
                    {{ $documents->total() }} Records
                </span>

            </div>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th class="ps-3">Document Information</th>
                            <th>Category</th>
                            <th>Document Date</th>
                            <th>Originating Office</th>
                            <th>Uploaded By</th>
                            <th class="text-center">Actions</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($documents as $document)

                            <tr>

                                {{-- Document Information --}}
                                <td class="ps-3">

                                    <div class="fw-semibold">
                                        {{ $document->subject }}
                                    </div>

                                    <small class="text-muted">

                                        @if($document->document_number)
                                            No. {{ $document->document_number }}
                                        @else
                                            No reference number
                                        @endif

                                    </small>

                                </td>

                                {{-- Category --}}
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $document->document_type }}
                                    </span>
                                </td>

                                {{-- Document Date --}}
                                <td>
                                    {{ $document->document_date
                                        ? $document->document_date->format('M d, Y')
                                        : '—' }}
                                </td>

                                {{-- Originating Office --}}
                                <td>
                                    {{ $document->originating_office ?: '—' }}
                                </td>

                                {{-- Uploaded By --}}
                                <td>
                                    {{ $document->uploadedBy?->name ?? 'Unknown' }}
                                </td>

                                {{-- Actions --}}
                                <td class="text-center">

                                    <div class="d-flex justify-content-center gap-1">

                                        <a href="{{ route('document-archives.show', $document) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="View Document">

                                            <i class="bi bi-eye"></i>

                                        </a>

                                        <a href="{{ route('document-archives.edit', $document) }}"
                                           class="btn btn-sm btn-outline-secondary"
                                           title="Edit Document">

                                            <i class="bi bi-pencil"></i>

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="text-center py-5">

                                    <i class="bi bi-folder-x display-5 text-muted"></i>

                                    <h6 class="fw-bold mt-3">
                                        No Archived Documents Found
                                    </h6>

                                    <p class="text-muted mb-3">
                                        There are no documents matching your search.
                                    </p>

                                    <a href="{{ route('document-archives.create') }}"
                                       class="btn btn-primary btn-sm">

                                        <i class="bi bi-plus-circle me-1"></i>
                                        Upload First Document

                                    </a>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        {{-- Pagination --}}
        @if($documents->hasPages())

            <div class="card-footer bg-white">

                {{ $documents->links() }}

            </div>

        @endif

    </div>

</div>

@endsection
