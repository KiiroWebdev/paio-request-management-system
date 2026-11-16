@extends('layouts.master')

@section('page-title', 'Request Kanban Board')

@section('content')
<div class="container-fluid">

    <x-admin.page-header
        title="Request Kanban Board"
        subtitle="Track document requests throughout the PAIO workflow">

        <a href="{{ route('requests.index') }}" class="btn btn-outline-secondary">
    <i class="bi bi-list-ul me-1"></i> List View
</a>

<a href="{{ route('requests.kanban') }}"
   class="btn btn-outline-primary">
    <i class="bi bi-kanban me-1"></i> Kanban Board
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

  @php
    $totalServices = $item->services->count();

    $completedServices = $item->services->filter(
        fn($service) => ($service->pivot->status ?? 'Pending') === 'Done'
    )->count();

    $progressPercentage = $totalServices > 0
        ? round(($completedServices / $totalServices) * 100)
        : 0;
@endphp                         
@if($item->services->isNotEmpty())
<div class="mb-2">
    <div class="d-flex justify-content-between align-items-center mb-1">
        <small class="fw-bold text-muted">
            Service Completion
        </small>

        <small class="fw-bold">
            {{ $completedServices }} of {{ $totalServices }}
            ({{ $progressPercentage }}%)
        </small>
    </div>

    <div class="progress" style="height: 8px;">
        <div
            class="progress-bar bg-success"
            role="progressbar"
            style="width: {{ $progressPercentage }}%;"
            aria-valuenow="{{ $progressPercentage }}"
            aria-valuemin="0"
            aria-valuemax="100"
        ></div>
    </div>
</div>
    
<div class="kanban-services-list mt-2">
    <div class="small fw-bold text-muted mb-2">
        Requested Services
    </div>

    @foreach($item->services as $service)
        @php
            $serviceStatus = $service->pivot->status ?? 'Pending';

            $serviceStatusClass = match($serviceStatus) {
                'Pending' => 'bg-secondary',
                'Ongoing' => 'bg-primary',
                'Done' => 'bg-success',
                default => 'bg-secondary',
            };
        @endphp

        <div class="kanban-service-row">
            <span class="small">
                {{ $service->name }}
            </span>

            @if(
                $item->status === 'Ongoing'
                && $item->approval_status === 'Approved'
            )
                <select
                    class="form-select form-select-sm service-progress-select"
                    data-service-id="{{ $service->id }}"
                    data-current-status="{{ $serviceStatus }}"
                >
                    <option value="Pending"
                        @selected($serviceStatus === 'Pending')>
                        Pending
                    </option>

                    <option value="Ongoing"
                        @selected($serviceStatus === 'Ongoing')>
                        Ongoing
                    </option>

                    <option value="Done"
                        @selected($serviceStatus === 'Done')>
                        Done
                    </option>
                </select>
            @else
                <span class="badge {{ $serviceStatusClass }}">
                    {{ $serviceStatus }}
                </span>
            @endif
        </div>
    @endforeach
</div>

@endif


                            
@if($item->assignedPersonnel->isNotEmpty())
    <div class="kanban-detail mt-2">
        <i class="bi bi-people me-1"></i>
        <strong>Assigned:</strong>
        {{ $item->assignedPersonnel->pluck('name')->implode(', ') }}
    </div>
@elseif($item->assignedTo)
    <div class="kanban-detail mt-2">
        <i class="bi bi-person-check me-1"></i>
        <strong>Assigned:</strong>
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
    <span class="small">
    <i class="bi bi-calendar-event me-1"></i>

    @if(!$dueDate)
        <span class="text-muted">
            No Due Date
        </span>

    @elseif($daysUntilDue < 0)
        <span class="text-danger fw-bold">
            Overdue: {{ $dueDate->format('M d, Y') }}
        </span>

    @elseif($daysUntilDue === 0)
        <span class="text-warning fw-bold">
            Due Today: {{ $dueDate->format('M d, Y') }}
        </span>

    @elseif($daysUntilDue <= 3)
        <span class="text-warning fw-bold">
            Due Soon: {{ $dueDate->format('M d, Y') }}
        </span>

    @else
        <span class="text-success">
            On Track: {{ $dueDate->format('M d, Y') }}
        </span>
    @endif
</span>

    <div class="d-flex gap-1">
        @if(
            auth()->user()?->isAdmin()
            && $item->status === 'Assigned'
        )
            <button
                type="button"
                class="btn btn-sm btn-outline-secondary edit-assignment-btn"
                data-request-id="{{ $item->id }}"
                data-personnel-ids="{{ $item->assignedPersonnel->pluck('id')->implode(',') }}"
                title="Edit assigned personnel"
                aria-label="Edit assignment for {{ $item->control_no }}">
                <i class="bi bi-pencil-square"></i>
                Edit
            </button>
        @endif

        <a href="{{ route('requests.show', $item) }}"
           class="btn btn-sm btn-outline-primary"
           title="View request"
           aria-label="View {{ $item->control_no }}">
            <i class="bi bi-eye"></i>
            View
        </a>
    </div>
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
                    Select one or more personnel to handle this request.
                </p>

                <label class="form-label fw-semibold">
                    Personnel Directory
                </label>

                <div class="border rounded p-3"
                     style="max-height: 300px; overflow-y: auto;">

                    @forelse($personnels as $personnel)
                        <div class="form-check mb-3">
                            <input
                                class="form-check-input kanban-personnel-checkbox"
                                type="checkbox"
                                value="{{ $personnel->id }}"
                                id="kanbanPersonnel{{ $personnel->id }}">

                            <label
                                class="form-check-label"
                                for="kanbanPersonnel{{ $personnel->id }}">

                                <span class="fw-semibold">
                                    {{ $personnel->name }}
                                </span>

                                @if($personnel->position || $personnel->department)
                                    <br>
                                    <small class="text-muted">
                                        {{ $personnel->position ?? '' }}

                                        @if($personnel->position && $personnel->department)
                                            &middot;
                                        @endif

                                        {{ $personnel->department ?? '' }}
                                    </small>
                                @endif
                            </label>
                        </div>
                    @empty
                        <p class="text-muted mb-0">
                            No active personnel found. Add personnel in the
                            Personnel Directory first.
                        </p>
                    @endforelse

                </div>

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


<div class="modal fade" id="editAssignmentModal" tabindex="-1"
     aria-labelledby="editAssignmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="editAssignmentModalLabel">
                    Edit Assigned Personnel
                </h5>
                <button type="button" class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <p class="text-muted">
                    Update the personnel assigned to this request.
                    Existing selections are preselected.
                </p>

                <label class="form-label fw-semibold">
                    Personnel Directory
                </label>

                <div class="border rounded p-3"
                     style="max-height: 300px; overflow-y: auto;">

                    @forelse($personnels as $personnel)
                        <div class="form-check mb-3">
                            <input
                                class="form-check-input edit-personnel-checkbox"
                                type="checkbox"
                                value="{{ $personnel->id }}"
                                id="editKanbanPersonnel{{ $personnel->id }}">

                            <label
                                class="form-check-label"
                                for="editKanbanPersonnel{{ $personnel->id }}">

                                <span class="fw-semibold">
                                    {{ $personnel->name }}
                                </span>

                                @if($personnel->position || $personnel->department)
                                    <br>
                                    <small class="text-muted">
                                        {{ $personnel->position ?? '' }}

                                        @if($personnel->position && $personnel->department)
                                            &middot;
                                        @endif

                                        {{ $personnel->department ?? '' }}
                                    </small>
                                @endif
                            </label>
                        </div>
                    @empty
                        <p class="text-muted mb-0">
                            No active personnel found.
                        </p>
                    @endforelse

                </div>

                <div id="editAssignmentError"
                     class="text-danger small mt-2"></div>
            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">
                    Cancel
                </button>

                <button type="button"
                        class="btn btn-primary"
                        id="saveEditedAssignment">
                    <i class="bi bi-save me-1"></i>
                    Save Changes
                </button>
            </div>

        </div>
    </div>
</div>


    

{{-- Approval Decision Modal --}}
<div class="modal fade" id="kanbanDecisionModal" tabindex="-1"
     aria-labelledby="kanbanDecisionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="kanbanDecisionModalLabel">
                    Process Request
                </h5>
                <button type="button" class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <p class="mb-2">
                    Choose what to do with this pending request.
                </p>

                <label for="kanbanDecisionSelect" class="form-label">
                    Decision
                </label>

                <select id="kanbanDecisionSelect" class="form-select">
                    <option value="Approve">Approve for Incoming</option>
                    <option value="Decline">Decline</option>
                    <option value="Cancel">Cancel Request</option>
                </select>

                {{-- Approval Note --}}
                <div id="kanbanApprovalNoteGroup" class="mt-3">
                    <label for="kanbanApprovalReviewNote" class="form-label">
                        Approval Note
                    </label>

                    <textarea id="kanbanApprovalReviewNote"
                              class="form-control"
                              rows="3"
                              maxlength="1000"
                              placeholder="Enter an optional approval note"></textarea>

                    <small class="text-muted">
                        Optional. Maximum of 1,000 characters.
                    </small>
                </div>

                {{-- Decline / Cancel Reason --}}
                <div id="kanbanReasonGroup" class="mt-3 d-none">
                    <label for="kanbanDecisionReason" class="form-label">
                        Reason <span class="text-danger">*</span>
                    </label>

                    <textarea id="kanbanDecisionReason"
                              class="form-control"
                              rows="3"
                              maxlength="1000"
                              placeholder="Enter the reason for this decision"></textarea>
                </div>

                <div id="kanbanDecisionError"
                     class="text-danger small mt-2"></div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">
                    Back
                </button>

                <button type="button" class="btn btn-primary"
                        id="confirmKanbanDecision">
                    Confirm Decision
                </button>
            </div>

        </div>
    </div>
</div>



<style>
    /* Search */
    .kanban-search {
        width: min(100%, 380px);
    }

    /* Main board: 3 columns */
    .kanban-board {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
        width: 100%;
        overflow: visible;
        max-height: none;
        align-items: start;
        padding: 2px 2px 18px;
    }

    /* Workflow columns */
    .kanban-column {
        min-width: 0;
        background: #f1f3f5;
        border-radius: 12px;
        overflow: visible;
        border: 1px solid #e2e5e9;
    }

    .kanban-column-header {
        background: #fff;
        padding: 12px 14px;
        min-height: 76px;
        border-radius: 11px 11px 0 0;
        box-shadow: 0 1px 2px rgba(0, 0, 0, .04);
    }

    .kanban-column-header h6 {
        font-size: .9rem;
        font-weight: 700;
    }

    /* Cards container */
    .kanban-cards {
        display: flex;
        flex-direction: column;
        gap: 9px;
        padding: 10px;
        min-height: 120px;
    }

    /* Compact request cards */
    .kanban-card {
        background: #fff;
        border: 1px solid #e2e5e9;
        border-radius: 9px;
        padding: 10px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .04);
        transition: box-shadow .2s, transform .2s;
        overflow-wrap: anywhere;
    }

   .kanban-card {
    background: #fff;
    border: 1px solid #e2e5e9;
    border-radius: 9px;
    padding: 10px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, .04);
    transition: background-color .2s ease,
                border-color .2s ease,
                box-shadow .2s ease,
                transform .2s ease;
    overflow-wrap: anywhere;
}

.kanban-card:hover {
    background-color: #eff6ff;
    border-color: #60a5fa;
    box-shadow: 0 5px 14px rgba(37, 99, 235, .12);
    transform: translateY(-2px);
}

    .kanban-control {
        color: #495057;
        font-size: .72rem;
        font-weight: 700;
    }

    .kanban-subject {
        font-size: .86rem;
        line-height: 1.35;
        margin-bottom: 7px;
        color: #212529;
    }

    .kanban-detail {
        font-size: .75rem;
        color: #6c757d;
        margin-top: 4px;
    }

    /* Individual services */
    .kanban-services {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        margin-top: 8px;
    }

    .kanban-services-list {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.kanban-service-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    padding: 6px 8px;
    background: #fff;
    border: 1px solid #e2e5e9;
    border-radius: 7px;
}

.kanban-service-row > span:first-child {
    min-width: 0;
    overflow-wrap: anywhere;
}

.kanban-service-row .form-select {
    width: 105px;
    flex-shrink: 0;
    font-size: .75rem;
    padding-top: 4px;
    padding-bottom: 4px;
}

    .kanban-services .badge {
        white-space: normal;
        text-align: left;
        font-weight: 500;
    }

    /* Card footer */
    .kanban-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
        border-top: 1px solid #edf0f2;
        padding-top: 8px;
        margin-top: 9px;
    }

    /* Empty column */
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

    /* Search filtering */
    .kanban-card.is-hidden {
        display: none;
    }

    /* Drag and drop */
    .kanban-card[draggable="true"] {
        cursor: grab;
    }

    
/* Card being dragged */
.kanban-card.is-dragging {
    opacity: .4;
    transform: scale(.97);
    cursor: grabbing;
}

/* Highlight the entire destination column */
.kanban-column.is-drag-target {
    background: #f0f7ff;
    border: 2px solid #3b82f6;
    box-shadow: 0 4px 14px rgba(59, 130, 246, .12);
    transition: all .2s ease;
}

/* Highlight the drop area */
.kanban-cards.is-drag-over {
    background: #eaf3ff;
    outline: none;
    border: 2px solid #3b82f6;
    border-radius: 10px;
    min-height: 150px;
    box-shadow: inset 0 0 0 2px rgba(59, 130, 246, .08);
    transition: all .2s ease;
}

/* Smooth card appearance after moving */
.kanban-card.just-moved {
    animation: kanbanCardMoved .35s ease;
}

@keyframes kanbanCardMoved {
    0% {
        opacity: .5;
        transform: translateY(8px);
    }

    100% {
        opacity: 1;
        transform: translateY(0);
    }
}


    /* Tablet: 2 columns */
    @media (max-width: 1100px) {
        .kanban-board {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    /* Mobile: 1 column */
    @media (max-width: 768px) {
        .kanban-board {
            grid-template-columns: minmax(0, 1fr);
        }

        .kanban-toolbar {
            align-items: stretch !important;
        }

        .kanban-search {
            width: 100%;
        }
    }
</style>

@push('scripts')
<script>


document.addEventListener('DOMContentLoaded', function () {
    const board = document.querySelector('.kanban-board');
    const searchInput = document.getElementById('kanbanSearch');
    const totalLabel = document.getElementById('kanbanTotal');

    const assignModalElement = document.getElementById('assignStaffModal');
    const personnelCheckboxes = document.querySelectorAll(
    '.kanban-personnel-checkbox'
);
    const assignError = document.getElementById('kanbanAssignError');
    const confirmAssignButton = document.getElementById('confirmKanbanAssign');
    
const editAssignmentModalElement =
    document.getElementById('editAssignmentModal');

const editPersonnelCheckboxes = document.querySelectorAll(
    '.edit-personnel-checkbox'
);

const editAssignmentError =
    document.getElementById('editAssignmentError');

const saveEditedAssignmentButton =
    document.getElementById('saveEditedAssignment');

const editAssignmentModal =
    editAssignmentModalElement && window.bootstrap?.Modal
        ? window.bootstrap.Modal.getOrCreateInstance(
            editAssignmentModalElement
        )
        : null;

let pendingEditAssignmentCard = null;
// Edit button handler
document.querySelectorAll('.edit-assignment-btn').forEach(function (button) {
    button.addEventListener('click', function () {
        if (!editAssignmentModal) {
            alert('The Edit Assignment dialog could not be opened.');
            return;
        }

        pendingEditAssignmentCard = button.closest('.kanban-card');

        const assignedIds = (
            button.dataset.personnelIds || ''
        ).split(',').filter(Boolean);

        editPersonnelCheckboxes.forEach(function (checkbox) {
            checkbox.checked = assignedIds.includes(checkbox.value);
        });

        editAssignmentError.textContent = '';
        saveEditedAssignmentButton.disabled = false;
        saveEditedAssignmentButton.innerHTML =
            '<i class="bi bi-save me-1"></i> Save Changes';

        editAssignmentModal.show();
    });
});


if (saveEditedAssignmentButton) {
    saveEditedAssignmentButton.addEventListener('click', async function () {
        if (isSaving || !pendingEditAssignmentCard) return;

        const personnelIds = Array.from(editPersonnelCheckboxes)
            .filter(function (checkbox) {
                return checkbox.checked;
            })
            .map(function (checkbox) {
                return checkbox.value;
            });

        if (personnelIds.length === 0) {
            editAssignmentError.textContent =
                'Please select at least one personnel.';
            return;
        }

        isSaving = true;
        saveEditedAssignmentButton.disabled = true;
        saveEditedAssignmentButton.textContent = 'Saving...';
        editAssignmentError.textContent = '';

        try {
            const response = await fetch(
                `/requests/${encodeURIComponent(
                    pendingEditAssignmentCard.dataset.requestId
                )}/assignment`,
                {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        personnel_ids: personnelIds
                    })
                }
            );

            const result = await response.json().catch(() => ({}));

            if (!response.ok) {
                throw new Error(
                    result.message || 'Unable to update the assignment.'
                );
            }

            window.location.reload();
        } catch (error) {
            editAssignmentError.textContent =
                error.message || 'Unable to update the assignment.';
        } finally {
            isSaving = false;
            saveEditedAssignmentButton.disabled = false;
            saveEditedAssignmentButton.innerHTML =
                '<i class="bi bi-save me-1"></i> Save Changes';
        }
    });
}


    const decisionModalElement = document.getElementById('kanbanDecisionModal');
const decisionSelect = document.getElementById('kanbanDecisionSelect');
const reasonGroup = document.getElementById('kanbanReasonGroup');
const reasonInput = document.getElementById('kanbanDecisionReason');
const decisionError = document.getElementById('kanbanDecisionError');
const confirmDecisionButton = document.getElementById('confirmKanbanDecision');
const approvalNoteGroup = document.getElementById(
    'kanbanApprovalNoteGroup'
);

const approvalNoteInput = document.getElementById(
    'kanbanApprovalReviewNote'
);

    if (!board) return;

    const csrfToken =
        document.querySelector('meta[name="csrf-token"]')?.content
        || '{{ csrf_token() }}';

   let draggedCard = null;
let pendingAssignmentCard = null;
let pendingDecisionCard = null;
let isSaving = false;



// ==========================================
// KANBAN AUTOMATIC SCROLL WHILE DRAGGING
// ==========================================

let autoScrollFrame = null;
let dragPointer = { x: 0, y: 0 };

const EDGE_SIZE = 90;
const MAX_SCROLL_SPEED = 14;

function stopAutoScroll() {
    if (autoScrollFrame !== null) {
        cancelAnimationFrame(autoScrollFrame);
        autoScrollFrame = null;
    }
}

function updateAutoScroll(event) {
    if (!draggedCard) return;

    dragPointer.x = event.clientX;
    dragPointer.y = event.clientY;

    if (autoScrollFrame !== null) return;

    function scrollStep() {
        if (!draggedCard) {
            stopAutoScroll();
            return;
        }

        const rect = board.getBoundingClientRect();

        let scrollX = 0;
        let scrollY = 0;

        if (dragPointer.y < rect.top + EDGE_SIZE) {
            scrollY = -MAX_SCROLL_SPEED;
        } else if (dragPointer.y > rect.bottom - EDGE_SIZE) {
            scrollY = MAX_SCROLL_SPEED;
        }

        if (dragPointer.x < rect.left + EDGE_SIZE) {
            scrollX = -MAX_SCROLL_SPEED;
        } else if (dragPointer.x > rect.right - EDGE_SIZE) {
            scrollX = MAX_SCROLL_SPEED;
        }

        if (scrollX !== 0 || scrollY !== 0) {
            board.scrollBy({
                left: scrollX,
                top: scrollY,
                behavior: 'auto'
            });

            autoScrollFrame = requestAnimationFrame(scrollStep);
        } else {
            autoScrollFrame = null;
        }
    }

    autoScrollFrame = requestAnimationFrame(scrollStep);
}

board.addEventListener('dragover', function (event) {
    if (draggedCard) {
        updateAutoScroll(event);
    }
});



    const assignModal = assignModalElement && window.bootstrap?.Modal
    ? window.bootstrap.Modal.getOrCreateInstance(assignModalElement)
    : null;

    const decisionModal = decisionModalElement && window.bootstrap?.Modal
    ? window.bootstrap.Modal.getOrCreateInstance(decisionModalElement)
    : null;

    const allowedDestinations = {
    Incoming: ['Assigned'],
    Assigned: ['Ongoing'],
    Ongoing: []
};

    // Save a workflow transition through Laravel.
   

async function moveRequest(card, destination, personnelIds = []) {
    const payload = {
        destination: destination
    };

    if (destination === 'Assigned') {
        payload.personnel_ids = personnelIds;
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

    // Find the destination column.
    const destinationColumn = Array.from(
        board.querySelectorAll('.kanban-column')
    ).find(function (column) {
        return column.dataset.column === destination;
    });

    if (destinationColumn) {
        const destinationZone = destinationColumn.querySelector('.kanban-cards');

        // Move the card visually after the server confirms success.
        if (destinationZone) {
            destinationZone.appendChild(card);
            card.dataset.currentStatus = destination;
            card.classList.remove('is-dragging');
            card.classList.add('just-moved');

            setTimeout(function () {
                card.classList.remove('just-moved');
            }, 500);
        }
    }

    // Display success notification.
    let notification = document.getElementById('kanbanMoveNotification');

    if (!notification) {
        notification = document.createElement('div');
        notification.id = 'kanbanMoveNotification';

        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 99999;
            background: #198754;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,.15);
            font-size: 14px;
            font-weight: 600;
            opacity: 0;
            transform: translateY(-10px);
            transition: all .25s ease;
        `;

        document.body.appendChild(notification);
    }

    notification.textContent =
        result.message || 'Request moved successfully!';

    requestAnimationFrame(function () {
        notification.style.opacity = '1';
        notification.style.transform = 'translateY(0)';
    });

    // Refresh after the animation and notification.
    setTimeout(function () {
        window.location.reload();
    }, 1200);
}


// Update an individual service's progress.
board.querySelectorAll('.service-progress-select').forEach(function (select) {
    select.addEventListener('change', async function () {
        const card = select.closest('.kanban-card');

        if (!card) return;

        const requestId = card.dataset.requestId;
        const serviceId = select.dataset.serviceId;
        const previousStatus = select.dataset.currentStatus;
        const newStatus = select.value;

        select.disabled = true;

        try {
            const response = await fetch(
                `/requests/${encodeURIComponent(requestId)}/services/${encodeURIComponent(serviceId)}/progress`,
                {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        status: newStatus
                    })
                }
            );

            const result = await response.json().catch(() => ({}));

            if (!response.ok) {
                throw new Error(
                    result.message || 'Unable to update service progress.'
                );
            }

            // Refresh the board so updated statuses and completed
            // requests are displayed correctly.
            window.location.reload();

        } catch (error) {
            alert(error.message || 'An error occurred.');

            select.value = previousStatus;
            select.disabled = false;
        }
    });
});



   // Allow pending requests to be dragged to the Incoming decision target.
// Approved requests retain the existing workflow restrictions.
board.querySelectorAll('.kanban-card').forEach(function (card) {
    const status = card.dataset.currentStatus;
    const approvalStatus = card.dataset.approvalStatus;

    card.draggable =
        (approvalStatus === 'Pending' && status === 'Incoming') ||
        (
            approvalStatus === 'Approved' &&
            ['Incoming', 'Assigned', 'Ongoing'].includes(status)
        );

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
            stopAutoScroll();
            card.classList.remove('is-dragging');

            board.querySelectorAll('.kanban-cards').forEach(function (zone) {
                zone.classList.remove('is-drag-over');
            });

            board.querySelectorAll('.kanban-column').forEach(function (column) {
    column.classList.remove('is-drag-target');
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
            column.classList.add('is-drag-target');
            event.dataTransfer.dropEffect = 'move';
        });

        dropZone.addEventListener('dragleave', function (event) {
           if (!column.contains(event.relatedTarget)) {
    dropZone.classList.remove('is-drag-over');
    column.classList.remove('is-drag-target');
}
        });

        dropZone.addEventListener('drop', async function (event) {
            event.preventDefault();
            stopAutoScroll();
            dropZone.classList.remove('is-drag-over');

            if (!draggedCard) return;

            const card = draggedCard;
            const destination = column.dataset.column;
            const currentStatus = card.dataset.currentStatus;
            // Pending requests use Incoming as their stored status,
// but appear in the For Approval column.
const isPendingApproval =
    card.dataset.approvalStatus === 'Pending';

const sourceColumn = card.closest('.kanban-column')?.dataset.column;

if (
    isPendingApproval &&
    sourceColumn === 'For Approval' &&
    destination === 'Incoming'
) {
    if (!decisionModal) {
        alert('The decision dialog could not be opened. Check that Bootstrap JavaScript is loaded.');
        return;
    }

    pendingDecisionCard = card;
    decisionSelect.value = 'Approve';
    reasonInput.value = '';
    reasonGroup.classList.add('d-none');
    decisionError.textContent = '';
    confirmDecisionButton.disabled = false;
    confirmDecisionButton.textContent = 'Confirm Decision';

    decisionModal.show();
    return;
}

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

personnelCheckboxes.forEach(function (checkbox) {
    checkbox.checked = false;
});

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

    
    // Toggle Approval Note and Reason fields based on decision.
if (decisionSelect) {
    decisionSelect.addEventListener('change', function () {
        const needsReason = ['Decline', 'Cancel'].includes(this.value);
        const needsApprovalNote = this.value === 'Approve';

        reasonGroup.classList.toggle('d-none', !needsReason);

        if (approvalNoteGroup) {
            approvalNoteGroup.classList.toggle(
                'd-none',
                !needsApprovalNote
            );
        }

        if (!needsReason) {
            reasonInput.value = '';
        }

        if (!needsApprovalNote && approvalNoteInput) {
            approvalNoteInput.value = '';
        }

        decisionError.textContent = '';
    });
}

    // Submit the selected approval decision to Laravel.
    if (confirmDecisionButton) {
        confirmDecisionButton.addEventListener('click', async function () {
            if (isSaving || !pendingDecisionCard) return;

            const decision = decisionSelect.value;
            const reason = reasonInput.value.trim();

            if (
                ['Decline', 'Cancel'].includes(decision) &&
                !reason
            ) {
                decisionError.textContent =
                    'Please provide a reason for this decision.';
                return;
            }

            isSaving = true;
            confirmDecisionButton.disabled = true;
            confirmDecisionButton.textContent = 'Processing...';
            decisionError.textContent = '';

            try {
                const response = await fetch(
                    `/requests/${encodeURIComponent(pendingDecisionCard.dataset.requestId)}/kanban-decision`,
                    {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
    decision: decision,
    reason: reason,
    approval_review_note:
        document.getElementById('kanbanApprovalReviewNote')?.value.trim() || ''
})
                    }
                );

                const result = await response.json().catch(() => ({}));

                if (!response.ok) {
                    throw new Error(
                        result.message || 'Unable to process this request.'
                    );
                }

                window.location.reload();
            } catch (error) {
                decisionError.textContent =
                    error.message || 'An error occurred while processing the request.';
            } finally {
                isSaving = false;
                confirmDecisionButton.disabled = false;
                confirmDecisionButton.textContent = 'Confirm Decision';
            }
        });
    }

    // Clear pending decision data when the modal closes.
    if (decisionModalElement) {
        decisionModalElement.addEventListener('hidden.bs.modal', function () {
            if (!isSaving) {
                pendingDecisionCard = null;

                if (decisionSelect) decisionSelect.value = 'Approve';
                if (reasonInput) reasonInput.value = '';
                if (reasonGroup) reasonGroup.classList.add('d-none');
                if (approvalNoteInput) approvalNoteInput.value = '';
                if (approvalNoteGroup) approvalNoteGroup.classList.add('d-none');
                if (decisionError) decisionError.textContent = '';
            }
        });
    }


    // Confirm the selected staff member and save the assignment.
   
if (confirmAssignButton) {
    confirmAssignButton.addEventListener('click', async function () {
        if (isSaving || !pendingAssignmentCard) return;

        const personnelIds = Array.from(personnelCheckboxes)
            .filter(function (checkbox) {
                return checkbox.checked;
            })
            .map(function (checkbox) {
                return checkbox.value;
            });

        if (personnelIds.length === 0) {
            assignError.textContent =
                'Please select at least one personnel.';
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
                personnelIds
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

  // Clear pending decision data when the modal closes.
if (decisionModalElement) {
    decisionModalElement.addEventListener('hidden.bs.modal', function () {
        if (!isSaving) {
            pendingDecisionCard = null;

            if (decisionSelect) decisionSelect.value = 'Approve';
            if (reasonInput) reasonInput.value = '';
            if (reasonGroup) reasonGroup.classList.add('d-none');
            if (approvalNoteInput) approvalNoteInput.value = '';

            if (approvalNoteGroup) {
                approvalNoteGroup.classList.remove('d-none');
            }

            if (decisionError) decisionError.textContent = '';
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