@props([
    'field',
    'name' => null,
    'value' => null,
])

@php
    $schedules = [];

    if (is_array($value)) {
        // The edit page already decoded the value.
        $schedules = $value;
    } elseif (is_string($value) && $value !== '') {
        // The value is still stored as JSON.
        $decoded = json_decode($value, true);

        if (is_array($decoded)) {
            $schedules = $decoded;
        }
    }

    if (empty($schedules)) {
        $schedules = [
            [
                'date' => '',
                'start_time' => '',
                'end_time' => '',
            ],
        ];
    }
@endphp

<div class="mb-4 schedule-field">

    <label class="form-label fw-semibold">
        {{ $field->label }}

        @if($field->required)
            <span class="text-danger">*</span>
        @endif
    </label>

    @if($field->help_text)
        <div class="form-text mb-2">
            {{ $field->help_text }}
        </div>
    @endif

    <div
        class="schedule-rows"
        data-field-name="{{ $name }}"
        data-required="{{ $field->required ? '1' : '0' }}"
    >

        @foreach($schedules as $index => $schedule)

            <div class="schedule-row card border mb-3">

                <div class="card-body">

                    <div class="row g-3 align-items-end">

                        {{-- Date --}}
                        <div class="col-md-5">

                            <label class="form-label">
                                Date
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="date"
                                name="{{ $name }}[{{ $index }}][date]"
                                value="{{ $schedule['date'] ?? '' }}"
                                class="form-control schedule-date"
                                required
                            >

                        </div>

                        {{-- Start Time --}}
                        <div class="col-md-3">

                            <label class="form-label">
                                Start Time
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="time"
                                name="{{ $name }}[{{ $index }}][start_time]"
                                value="{{ $schedule['start_time'] ?? '' }}"
                                class="form-control schedule-start"
                                required
                            >

                        </div>

                        {{-- End Time --}}
                        <div class="col-md-3">

                            <label class="form-label">
                                End Time
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="time"
                                name="{{ $name }}[{{ $index }}][end_time]"
                                value="{{ $schedule['end_time'] ?? '' }}"
                                class="form-control schedule-end"
                                required
                            >

                        </div>

                        {{-- Remove --}}
                        <div class="col-md-1">

                            <button
                                type="button"
                                class="btn btn-outline-danger remove-schedule"
                                title="Remove schedule"
                            >
                                <i class="bi bi-trash"></i>
                            </button>

                        </div>

                    </div>

                </div>

            </div>

        @endforeach

    </div>

    <button
        type="button"
        class="btn btn-outline-primary add-schedule"
    >
        <i class="bi bi-plus-circle me-1"></i>
        Add Another Schedule
    </button>

</div>

@once
    @push('scripts')

        <script>
        document.addEventListener('DOMContentLoaded', function () {

            document.querySelectorAll('.schedule-field').forEach(function (fieldContainer) {

                const rowsContainer =
                    fieldContainer.querySelector('.schedule-rows');

                const addButton =
                    fieldContainer.querySelector('.add-schedule');

                if (!rowsContainer || !addButton) {
                    return;
                }

                function updateRemoveButtons() {

                    const rows =
                        rowsContainer.querySelectorAll('.schedule-row');

                    rows.forEach(function (row) {

                        const button =
                            row.querySelector('.remove-schedule');

                        if (button) {
                            button.disabled = rows.length === 1;
                        }

                    });
                }

                function getNextIndex() {

                    const rows =
                        rowsContainer.querySelectorAll('.schedule-row');

                    let highestIndex = -1;

                    rows.forEach(function (row) {

                        row.querySelectorAll('input').forEach(function (input) {

                            const match =
                                input.name.match(/\[(\d+)\]/);

                            if (match) {
                                highestIndex =
                                    Math.max(
                                        highestIndex,
                                        parseInt(match[1])
                                    );
                            }

                        });

                    });

                    return highestIndex + 1;
                }

                addButton.addEventListener('click', function () {

                    const index = getNextIndex();

                    const baseName =
                        rowsContainer.dataset.fieldName;

                    const row =
                        document.createElement('div');

                    row.className =
                        'schedule-row card border mb-3';

                    row.innerHTML = `
                        <div class="card-body">

                            <div class="row g-3 align-items-end">

                                <div class="col-md-5">

                                    <label class="form-label">
                                        Date
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input
                                        type="date"
                                        name="${baseName}[${index}][date]"
                                        class="form-control schedule-date"
                                        required
                                    >

                                </div>

                                <div class="col-md-3">

                                    <label class="form-label">
                                        Start Time
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input
                                        type="time"
                                        name="${baseName}[${index}][start_time]"
                                        class="form-control schedule-start"
                                        required
                                    >

                                </div>

                                <div class="col-md-3">

                                    <label class="form-label">
                                        End Time
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input
                                        type="time"
                                        name="${baseName}[${index}][end_time]"
                                        class="form-control schedule-end"
                                        required
                                    >

                                </div>

                                <div class="col-md-1">

                                    <button
                                        type="button"
                                        class="btn btn-outline-danger remove-schedule"
                                        title="Remove schedule"
                                    >
                                        <i class="bi bi-trash"></i>
                                    </button>

                                </div>

                            </div>

                        </div>
                    `;

                    rowsContainer.appendChild(row);

                    updateRemoveButtons();
                });

                rowsContainer.addEventListener('click', function (event) {

                    const button =
                        event.target.closest('.remove-schedule');

                    if (!button) {
                        return;
                    }

                    const row =
                        button.closest('.schedule-row');

                    if (row) {
                        row.remove();
                    }

                    updateRemoveButtons();
                });

                updateRemoveButtons();

            });

        });
        </script>

    @endpush
@endonce