<div class="mb-3">

    <label class="form-label">

        Service Name

    </label>

    <input
        type="text"
        name="name"
        class="form-control @error('name') is-invalid @enderror"
        value="{{ old('name', $service->name ?? '') }}"
        required>

    @error('name')

        <div class="invalid-feedback">

            {{ $message }}

        </div>

    @enderror

</div>

<div class="mb-3">

    <label class="form-label">

        Description

    </label>

    <textarea
        name="description"
        rows="4"
        class="form-control">{{ old('description', $service->description ?? '') }}</textarea>

</div>

<div class="mb-3">

    <label class="form-label">

        Sort Order

    </label>

    <input
        type="number"
        name="sort_order"
        class="form-control"
        value="{{ old('sort_order', $service->sort_order ?? 0) }}">

</div>

<div class="form-check mb-4">

    <input
        type="checkbox"
        name="is_active"
        id="is_active"
        class="form-check-input"
        value="1"
        {{ old('is_active', $service->is_active ?? true) ? 'checked' : '' }}>

    <label
        class="form-check-label"
        for="is_active">

        Active Service

    </label>

</div>

<div class="text-end">

    <a
        href="{{ route('services.index') }}"
        class="btn btn-secondary">

        Cancel

    </a>

    <button
        type="submit"
        class="btn btn-primary">

        <i class="bi bi-check-circle me-1"></i>

        Save Service

    </button>

</div>