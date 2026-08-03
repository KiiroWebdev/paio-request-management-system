@extends('layouts.master')

@section('page-title', 'Field Options')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold">
                {{ $field->label }}
            </h2>

            <p class="text-muted mb-0">

                Service:
                <strong>{{ $service->name }}</strong>

            </p>

        </div>

        <a
            href="{{ route('services.fields.index', $service) }}"
            class="btn btn-secondary">

            <i class="bi bi-arrow-left"></i>

            Back

        </a>

    </div>

    <div class="card shadow-sm">

        <div class="card-header">

            <h5 class="mb-0">
                Field Options
            </h5>

        </div>

        <div class="card-body">

            <form
                action="{{ route('services.fields.options.store', [$service, $field]) }}"
                method="POST">

                @csrf

                <div class="row">

                    <div class="col-md-5">

    <label class="form-label">
        Label
    </label>

    <input
        id="option_label"
        type="text"
        name="label"
        class="form-control"
        placeholder="Conference"
        required>

</div>

<div class="col-md-5">

    <label class="form-label">Label</label>

    <input
        id="option_label"
        type="text"
        name="label"
        class="form-control"
        placeholder="Conference"
        required>

</div>

                    <div class="col-md-2">

                        <button
                            class="btn btn-primary w-100">

                            Add

                        </button>

                    </div>

                </div>

            </form>

            <hr>

            <table class="table table-striped">

                <thead>

                    <tr>

    <th width="60">#</th>

    <th>Label</th>

    <th>Value</th>

    <th width="120">Status</th>

    <th width="150">Actions</th>

</tr>

                </thead>

                <tbody>

@forelse($field->options as $option)

<tr>

    <td>{{ $loop->iteration }}</td>

    <td>

        {{ $option->label }}

    </td>

    <td>

        {{ $option->value }}

    </td>

    <td>

        @if($option->active)

            <span class="badge bg-success">

                Active

            </span>

        @else

            <span class="badge bg-secondary">

                Disabled

            </span>

        @endif

    </td>

    <td width="150">

        <a
            href="{{ route('services.fields.options.edit', [$service, $field, $option]) }}"
            class="btn btn-sm btn-outline-primary">

            <i class="bi bi-pencil"></i>

        </a>

        <form
            action="{{ route('services.fields.options.destroy', [$service, $field, $option]) }}"
            method="POST"
            class="d-inline"
            onsubmit="return confirm('Delete this option?');">

            @csrf
            @method('DELETE')

            <button
                class="btn btn-sm btn-outline-danger">

                <i class="bi bi-trash"></i>

            </button>

        </form>

    </td>

</tr>

@empty

<tr>

    <td colspan="5" class="text-center">

        No options yet.

    </td>

</tr>

@endforelse

</tbody>

            </table>

        </div>

    </div>

</div>

@endsection
<script>

document.addEventListener('DOMContentLoaded', function(){

     const label = document.getElementById('option_label');
    const value = document.getElementById('option_value');

    if (!label || !value) {
        return;
    }

    function generateValue() {
        value.value = label.value
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9]+/g, '_')
            .replace(/^_+|_+$/g, '');
    }

    label.addEventListener('input', generateValue);

    generateValue();
});
</script>