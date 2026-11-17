@extends('layouts.master')

@section('title', 'Document Categories')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Document Categories</h3>
            <p class="text-muted mb-0">
                Manage categories used in General Document Archives.
            </p>
        </div>

        <a href="{{ route('document-archives.index') }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Archives
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- Add Category --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="fw-semibold mb-0">Add New Category</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('document-archives.categories.store') }}"
                  method="POST">
                @csrf

                <div class="row align-items-end g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Category Name</label>
                        <input type="text"
                               name="name"
                               class="form-control"
                               value="{{ old('name') }}"
                               maxlength="255"
                               placeholder="Enter category name"
                               required>
                    </div>

                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-plus-lg me-1"></i> Add Category
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Category List --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="fw-semibold mb-0">Existing Categories</h5>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Category Name</th>
                        <th class="text-center">Documents</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td class="ps-4 fw-semibold">
    {{ $category->name }}
</td>

<td class="text-center">
    <span class="badge bg-primary rounded-pill">
        {{ number_format($category->documents_count) }}
    </span>
</td>

                            <td>
                                @if($category->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>

                            <td>{{ $category->created_at->format('M d, Y') }}</td>

                            <td class="text-end pe-4">
                                <button type="button"
                                        class="btn btn-sm btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editCategory{{ $category->id }}">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>

                                <form class="d-inline"
                                      action="{{ route('document-archives.categories.toggle-status', $category) }}"
                                      method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit"
                                            class="btn btn-sm {{ $category->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                            onclick="return confirm('Change this category status?')">
                                        {{ $category->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                No document categories found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white">
            {{ $categories->links() }}
        </div>
    </div>

    {{-- Edit Category Modals --}}
    @foreach($categories as $category)
        <div class="modal fade"
             id="editCategory{{ $category->id }}"
             tabindex="-1"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <form action="{{ route('document-archives.categories.update', $category) }}"
                          method="POST">
                        @csrf
                        @method('PUT')

                        <div class="modal-header">
                            <h5 class="modal-title">Edit Document Category</h5>
                            <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <label class="form-label fw-semibold">Category Name</label>
                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   value="{{ $category->name }}"
                                   maxlength="255"
                                   required>
                        </div>

                        <div class="modal-footer">
                            <button type="button"
                                    class="btn btn-secondary"
                                    data-bs-dismiss="modal">
                                Cancel
                            </button>

                            <button type="submit" class="btn btn-primary">
                                Save Changes
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    @endforeach

</div>
@endsection