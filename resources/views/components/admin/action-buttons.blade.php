@props([
    'edit',
    'delete',
    'modalId',
])

<div class="d-inline-flex gap-1">

    <a
        href="{{ $edit }}"
        class="btn btn-sm btn-outline-primary"
        title="Edit">

        <i class="bi bi-pencil"></i>

    </a>

    <button
        type="button"
        class="btn btn-sm btn-outline-danger"
        data-bs-toggle="modal"
        data-bs-target="#{{ $modalId }}"
        title="Delete">

        <i class="bi bi-trash"></i>

    </button>

</div>

<x-admin.delete-modal
    :id="$modalId"
    :action="$delete">

    {{ $slot }}

</x-admin.delete-modal>