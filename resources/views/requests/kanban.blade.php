@extends('layouts.master')

@section('page-title', 'Request Kanban Board')

@section('content')
<div class="container-fluid">

    <x-admin.page-header
        title="Request Kanban Board"
        subtitle="Track document requests throughout the PAIO workflow">

        <a href="{{ route('requests.index') }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-list-ul me-1"></i>
            List View
        </a>

        <a href="{{ route('request-wizard.services') }}"
           class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>
            New Request
        </a>
    </x-admin.page-header>

    <x-admin.breadcrumb
        :items="['Request Management', 'Kanban Board']" />

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="alert alert-info border-0 shadow-sm">
        <i class="bi bi-info-circle-fill me-2"></i>
        <strong>Workflow:</strong>
        Requests must be approved before they can proceed to Incoming
        and subsequent processing stages. Drag-and-drop actions will
        be connected to the system's validation rules in the next step.
    </div>

    @php
        $boardColumns = [
            'For Approval' => [
                'icon' => 'bi-hourglass-split',
                'color' => '#f0ad4e',
                'description' => 'Awaiting approval',
            ],
            'Incoming' => [
                'icon' => 'bi-inbox',
                'color' => '#0d6efd',
                'description' => 'Approved requests',
            ],
            'Assigned' => [
                'icon' => 'bi-person-check',
                'color' => '#0dcaf0',
                'description' => 'Assigned to personnel',
            ],
            'Ongoing' => [
                'icon' => 'bi-arrow-repeat',
                'color' => '#6f42c1',
                'description' => 'Work in progress',
            ],
            'Completed' => [
                'icon' => 'bi-check-circle',
                'color' => '#198754',
                'description' => 'Completed requests',
            ],
            'Declined' => [
                'icon' => 'bi-x-circle',
                'color' => '#dc3545',
                'description' => 'Declined requests',
            ],
            'Cancelled' => [
                'icon' => 'bi-slash-circle',
                'color' => '#6c757d',
                'description' => 'Cancelled requests',
            ],
        ];
    @endphp

    <div class="kanban-toolbar d-flex flex-wrap
                justify-content-between align-items-center gap-3 mb-3">
        <div class="text-muted small">
            <i class="bi bi-kanban me-1"></i>
            <span id="kanbanTotal">
                {{ collect($columns)->flatten(1)->count() }}
            </span>
            requests displayed
        </div>

        <div class="input-group kanban-search">
            <span class="input-group-text bg-white">
                <i class="bi bi-search"></i>
            </span>
            <input type="search"
                   id="kanbanSearch"
                   class="form-control"
                   placeholder="Search control no., subject, or requestor...">
        </div>
    </div>

    <div class="kanban-board">
        @foreach($boardColumns as $columnName => $settings)
            @php
                $cards = $columns[$columnName] ?? collect();
            @endphp

            <section class="kanban-column"
                     data-column="{{ $columnName }}">

                <div class="kanban-column-header"
                     style="border-top: 4px solid {{ $settings['color'] }}">

                    <div class="d-flex align-items-center gap-2">
                        <i class="bi {{ $settings['icon'] }}"
                           style="color: {{ $settings['color'] }}"></i>

                        <h6 class="mb-0 fw-bold">
                            {{ $columnName }}
                        </h6>

                        <span class="badge rounded-pill bg-light text-dark
                                     kanban-count">
                            {{ $cards->count() }}
                        </span>
                    </div>

                    <small class="text-muted">
                        {{ $settings['description'] }}
                    </small>
                </div>

                <div class="kanban-cards">
                    @forelse($cards as $item)
                        @php
                            $requestor = $item->contact?->full_name
                                ?? 'No requestor specified';

                            $subject = $item->subject
                                ?: 'Untitled request';

                            $searchText = strtolower(
                                ($item->control_no ?? '') . ' ' .
                                $subject . ' ' .
                                $requestor . ' ' .
                                ($item->organization?->name ?? '')
                            );

                            $priorityClass = match($item->priority) {
                                'Low' => 'bg-success',
                                'Normal' => 'bg-primary',
                                'High' => 'bg-warning text-dark',
                                'Urgent' => 'bg-danger',
                                default => 'bg-secondary',
                            };

                            $dueDate = $item->due_date
                                ? \Carbon\Carbon::parse($item->due_date)->startOfDay()
                                : null;

                            $daysUntilDue = $dueDate
                                ? (int) \Carbon\Carbon::today()->diffInDays($dueDate, false)
                                : null;
                        @endphp

                        <article class="kanban-card"
         data-request-id="{{ $item->id }}"
         data-current-status="{{ $item->status }}"
         data-approval-status="{{ $item->approval_status }}"
         data-search="{{ $searchText }}">

                            <div class="d-flex justify-content-between
                                        align-items-start gap-2 mb-2">

                                <span class="kanban-control">
                                    {{ $item->control_no }}
                                </span>

                                <span class="badge {{ $priorityClass }}">
                                    {{ $item->priority ?? 'Normal' }}
                                </span>
                            </div>

                            <h6 class="kanban-subject">
                                {{ $subject }}
                            </h6>

                            <div class="kanban-detail">
                                <i class="bi bi-person me-1"></i>
                                {{ $requestor }}
                            </div>

                            @if($item->organization)
                                <div class="kanban-detail">
                                    <i class="bi bi-building me-1"></i>
                                    {{ $item->organization->name }}
                                </div>
                            @endif

                            @if($item->services->isNotEmpty())
                                <div class="kanban-services">
                                    @foreach($item->services as $service)
                                        <span class="badge bg-light text-primary border">
                                            {{ $service->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            @if($item->assignedTo)
                                <div class="kanban-detail mt-2">
                                    <i class="bi bi-person-check me-1"></i>
                                    {{ $item->assignedTo->name }}
                                </div>
                            @endif

                            @if($dueDate)
                                <div class="mt-2">
                                    @if($daysUntilDue < 0)
                                        <span class="badge bg-danger">
                                            <i class="bi bi-exclamation-circle me-1"></i>
                                            Overdue
                                        </span>
                                    @elseif($daysUntilDue === 0)
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-clock me-1"></i>
                                            Due Today
                                        </span>
                                    @elseif($daysUntilDue <= 3)
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-calendar-event me-1"></i>
                                            Due Soon
                                        </span>
                                    @else
                                        <span class="kanban-detail">
                                            <i class="bi bi-calendar3 me-1"></i>
                                            Due {{ $dueDate->format('M d, Y') }}
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <div class="kanban-card-footer">
                                <span class="small text-muted">
                                    {{ $item->created_at?->format('M d, Y') }}
                                </span>

                                <a href="{{ route('requests.show', $item) }}"
                                   class="btn btn-sm btn-outline-primary"
                                   title="View request"
                                   aria-label="View {{ $item->control_no }}">
                                    <i class="bi bi-eye"></i>
                                    View
                                </a>
                            </div>
                        </article>
                    @empty
                        <div class="kanban-empty">
                            <i class="bi bi-inbox"></i>
                            <p class="mb-0">No requests</p>
                        </div>
                    @endforelse
                </div>
            </section>
        @endforeach
    </div>

</div>


<div class="modal fade" id="assignStaffModal" tabindex="-1"
     aria-labelledby="assignStaffModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignStaffModalLabel">
                    Assign Request
                </h5>
                <button type="button" class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <p class="text-muted">
                    Select an active staff member to handle this request.
                </p>

                <label for="kanbanStaffSelect" class="form-label">
                    Staff Member
                </label>

                <select id="kanbanStaffSelect" class="form-select">
                    <option value="">Select staff member</option>

                    @foreach($staffUsers as $staff)
                        <option value="{{ $staff->id }}">
                            {{ $staff->name }}
                        </option>
                    @endforeach
                </select>

                <div id="kanbanAssignError"
                     class="text-danger small mt-2"></div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">
                    Cancel
                </button>

                <button type="button" class="btn btn-primary"
                        id="confirmKanbanAssign">
                    <i class="bi bi-person-check me-1"></i>
                    Assign Request
                </button>
            </div>
        </div>
    </div>
</div>


<style>
    .kanban-search {
        width: min(100%, 380px);
    }

    .kanban-board {
        display: grid;
        grid-template-columns: repeat(7, minmax(255px, 1fr));
        gap: 14px;
        overflow-x: auto;
        align-items: start;
        padding: 2px 2px 18px;
    }

    .kanban-column {
        min-width: 255px;
        background: #f1f3f5;
        border-radius: 10px;
        overflow: hidden;
    }

    .kanban-column-header {
        background: #fff;
        padding: 14px;
        min-height: 78px;
        border-radius: 10px 10px 0 0;
        box-shadow: 0 1px 2px rgba(0, 0, 0, .04);
    }

    .kanban-column-header h6 {
        font-size: .9rem;
    }

    .kanban-cards {
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding: 10px;
        min-height: 150px;
    }

    .kanban-card {
        background: #fff;
        border: 1px solid #e2e5e9;
        border-radius: 8px;
        padding: 12px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, .035);
        transition: box-shadow .2s, transform .2s;
        overflow-wrap: anywhere;
    }

    .kanban-card:hover {
        box-shadow: 0 5px 14px rgba(0, 0, 0, .09);
        transform: translateY(-1px);
    }

    .kanban-control {
        color: #495057;
        font-size: .72rem;
        font-weight: 700;
    }

    .kanban-subject {
        font-size: .88rem;
        line-height: 1.4;
        margin-bottom: 10px;
        color: #212529;
    }

    .kanban-detail {
        font-size: .77rem;
        color: #6c757d;
        margin-top: 5px;
    }

    .kanban-services {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        margin-top: 9px;
    }

    .kanban-services .badge {
        white-space: normal;
        text-align: left;
        font-weight: 500;
    }

    .kanban-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
        border-top: 1px solid #edf0f2;
        padding-top: 10px;
        margin-top: 12px;
    }

    .kanban-empty {
        text-align: center;
        color: #8a929a;
        padding: 24px 8px;
        font-size: .85rem;
    }

    .kanban-empty i {
        display: block;
        font-size: 1.5rem;
        margin-bottom: 5px;
    }

    .kanban-card.is-hidden {
        display: none;
    }

    @media (max-width: 768px) {
        .kanban-toolbar {
            align-items: stretch !important;
        }

        .kanban-search {
            width: 100%;
        }
    }

    .kanban-card[draggable="true"] {
    cursor: grab;
}

.kanban-card.is-dragging {
    opacity: 0.45;
}

.kanban-cards.is-drag-over {
    background: #e2e8f0;
    outline: 2px dashed #64748b;
    outline-offset: -4px;
    border-radius: 8px;
}

.kanban-card.is-hidden {
    display: none;
}
</style>

@push('scripts')
<script>


document.addEventListener('DOMContentLoaded', function () {
    const board = document.querySelector('.kanban-board');
    const searchInput = document.getElementById('kanbanSearch');
    const totalLabel = document.getElementById('kanbanTotal');

    const assignModalElement = document.getElementById('assignStaffModal');
    const staffSelect = document.getElementById('kanbanStaffSelect');
    const assignError = document.getElementById('kanbanAssignError');
    const confirmAssignButton = document.getElementById('confirmKanbanAssign');

    if (!board) return;

    const csrfToken =
        document.querySelector('meta[name="csrf-token"]')?.content
        || '{{ csrf_token() }}';

    let draggedCard = null;
    let pendingAssignmentCard = null;
    let isSaving = false;

    const assignModal = assignModalElement && window.bootstrap?.Modal
    ? window.bootstrap.Modal.getOrCreateInstance(assignModalElement)
    : null;

    const allowedDestinations = {
        Incoming: ['Assigned'],
        Assigned: ['Ongoing'],
        Ongoing: ['Completed']
    };

    // Save a workflow transition through Laravel.
    async function moveRequest(card, destination, assignedTo = null) {
        const payload = {
            destination: destination
        };

        if (destination === 'Assigned') {
            payload.assigned_to = assignedTo;
        }

        const response = await fetch(
            `/requests/${encodeURIComponent(card.dataset.requestId)}/kanban-move`,
            {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(payload)
            }
        );

        const result = await response.json().catch(() => ({}));

        if (!response.ok) {
            throw new Error(
                result.message || 'Unable to update this request.'
            );
        }

        window.location.reload();
    }

    // Enable dragging only for approved requests in active workflow stages.
    board.querySelectorAll('.kanban-card').forEach(function (card) {
        const status = card.dataset.currentStatus;
        const approved = card.dataset.approvalStatus === 'Approved';

        card.draggable =
            approved &&
            ['Incoming', 'Assigned', 'Ongoing'].includes(status);

        card.addEventListener('dragstart', function (event) {
            if (!card.draggable) {
                event.preventDefault();
                return;
            }

            draggedCard = card;
            card.classList.add('is-dragging');

            event.dataTransfer.effectAllowed = 'move';
            event.dataTransfer.setData(
                'text/plain',
                card.dataset.requestId
            );
        });

        card.addEventListener('dragend', function () {
            card.classList.remove('is-dragging');

            board.querySelectorAll('.kanban-cards').forEach(function (zone) {
                zone.classList.remove('is-drag-over');
            });

            draggedCard = null;
        });
    });

    // Handle the board's drop zones.
    board.querySelectorAll('.kanban-column').forEach(function (column) {
        const dropZone = column.querySelector('.kanban-cards');

        dropZone.addEventListener('dragover', function (event) {
            if (!draggedCard) return;

            event.preventDefault();
            dropZone.classList.add('is-drag-over');
            event.dataTransfer.dropEffect = 'move';
        });

        dropZone.addEventListener('dragleave', function (event) {
            if (!dropZone.contains(event.relatedTarget)) {
                dropZone.classList.remove('is-drag-over');
            }
        });

        dropZone.addEventListener('drop', async function (event) {
            event.preventDefault();
            dropZone.classList.remove('is-drag-over');

            if (!draggedCard) return;

            const card = draggedCard;
            const destination = column.dataset.column;
            const currentStatus = card.dataset.currentStatus;

            if (
                !(allowedDestinations[currentStatus] || [])
                    .includes(destination)
            ) {
                alert(
                    'That move is not allowed. Follow the request workflow.'
                );
                return;
            }

            // Assignment requires selecting a staff member first.
            if (destination === 'Assigned') {
                if (!assignModal) {
                    alert(
                        'The assignment dialog could not be opened. ' +
                        'Please check that Bootstrap JavaScript is loaded.'
                    );
                    return;
                }

                pendingAssignmentCard = card;
                staffSelect.value = '';
                assignError.textContent = '';
                confirmAssignButton.disabled = false;
                confirmAssignButton.innerHTML =
                    '<i class="bi bi-person-check me-1"></i> Assign Request';

                assignModal.show();
                return;
            }

            try {
                await moveRequest(card, destination);
            } catch (error) {
                alert(error.message || 'An error occurred while moving the request.');
            }
        });
    });

    // Confirm the selected staff member and save the assignment.
    if (confirmAssignButton) {
        confirmAssignButton.addEventListener('click', async function () {
            if (isSaving || !pendingAssignmentCard) return;

            const staffId = staffSelect.value;

            if (!staffId) {
                assignError.textContent = 'Please select a staff member.';
                return;
            }

            isSaving = true;
            confirmAssignButton.disabled = true;
            confirmAssignButton.textContent = 'Assigning...';
            assignError.textContent = '';

            try {
                await moveRequest(
                    pendingAssignmentCard,
                    'Assigned',
                    staffId
                );
            } catch (error) {
                assignError.textContent =
                    error.message || 'Unable to assign this request.';
            } finally {
                isSaving = false;
                confirmAssignButton.disabled = false;
                confirmAssignButton.innerHTML =
                    '<i class="bi bi-person-check me-1"></i> Assign Request';
            }
        });
    }

    // Clear the pending assignment when the modal is cancelled or closed.
    if (assignModalElement) {
        assignModalElement.addEventListener('hidden.bs.modal', function () {
            if (!isSaving) {
                pendingAssignmentCard = null;

                if (staffSelect) staffSelect.value = '';
                if (assignError) assignError.textContent = '';
            }
        });
    }

    // Search cards without changing their workflow status.
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const query = this.value.trim().toLowerCase();
            let total = 0;

            board.querySelectorAll('.kanban-column').forEach(function (column) {
                let count = 0;

                column.querySelectorAll('.kanban-card').forEach(function (card) {
                    const matches =
                        (card.dataset.search || '').includes(query);

                    card.classList.toggle('is-hidden', !matches);

                    if (matches) {
                        total++;
                        count++;
                    }
                });

                const countLabel = column.querySelector('.kanban-count');

                if (countLabel) {
                    countLabel.textContent = count;
                }
            });

            if (totalLabel) {
                totalLabel.textContent = total;
            }
        });
    }
});


</script>
@endpush
@endsection