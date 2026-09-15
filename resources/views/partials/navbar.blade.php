@php

    // Due-date notifications
    $dueNotificationCount = \App\Models\DocumentRequest::whereNotIn('status', [
        'Completed',
        'Archived',
    ])
    ->whereNotIn('approval_status', [
        'Declined',
        'Cancelled',
    ])
    ->whereNotNull('due_date')
    ->whereDate('due_date', '<=', today()->addDays(3))
    ->count();


    // Approval notifications
    $approvalNotificationCount = 0;

    if (auth()->check() && auth()->user()->role === 'Admin') {

        $approvalNotificationCount = \App\Models\DocumentRequest::where(
            'approval_status',
            'Pending'
        )->count();

    }


    // Total notifications
  $assignedNotificationCount = 0;

if (auth()->check()) {

    $assignedNotificationCount = \App\Models\DocumentRequest::where(
        'assigned_to',
        auth()->id()
    )
    ->where('status', 'Assigned')
    ->whereNotIn('approval_status', [
        'Declined',
        'Cancelled',
    ])
    ->count();

}

$ongoingNotificationCount = 0;

if (auth()->check()) {

    $ongoingNotificationCount = \App\Models\DocumentRequest::where(
        'assigned_to',
        auth()->id()
    )
    ->where('status', 'Ongoing')
    ->whereNotIn('approval_status', [
        'Declined',
        'Cancelled',
    ])
    ->count();

}

$notificationCount =
    $dueNotificationCount +
    $approvalNotificationCount +
    $assignedNotificationCount +
    $ongoingNotificationCount;

@endphp

<nav class="navbar navbar-expand-lg bg-white shadow-sm px-4 py-3">

    {{-- Sidebar Toggle --}}
    <button
        id="sidebarToggle"
        class="btn btn-outline-secondary me-3">

        <i class="bi bi-list fs-4"></i>

    </button>

    {{-- Dynamic Page Title --}}
    <div>

        <h4 class="mb-0 fw-bold">

            @yield('page-title', 'Dashboard')

        </h4>

        <small class="text-muted">

            Welcome back,
            {{ Auth::user()->name }}

        </small>

    </div>

    <div class="ms-auto d-flex align-items-center">

        {{-- Search --}}
        <div
            class="input-group me-3"
            style="width:320px;">

            <span class="input-group-text bg-white">

                <i class="bi bi-search"></i>

            </span>

            <input
                class="form-control"
                placeholder="Search...">

        </div>

        {{-- Notifications --}}
<div class="dropdown me-3">

    <button
        class="btn btn-light position-relative"
        type="button"
        data-bs-toggle="dropdown"
        aria-expanded="false">

        <i class="bi bi-bell fs-5"></i>

        @if($notificationCount > 0)

            <span
                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">

                {{ $notificationCount }}

            </span>

        @endif

    </button>

    <div
        class="dropdown-menu dropdown-menu-end shadow"
        style="width: 360px;">

        <div class="px-3 py-2 border-bottom">

            <div class="d-flex justify-content-between align-items-center">

                <strong>
                    <i class="bi bi-bell me-1"></i>
                    Notifications
                </strong>

                @if($notificationCount > 0)

                    <span class="badge bg-danger">
                        {{ $notificationCount }}
                    </span>

                @endif

            </div>

        </div>

        @if($notificationCount > 0)


       @php

    $notifications = collect();

    /*
    |--------------------------------------------------------------------------
    | Approval Notifications
    |--------------------------------------------------------------------------
    */

    if (auth()->check() && auth()->user()->role === 'Admin') {

        $approvalNotifications = \App\Models\DocumentRequest::where(
            'approval_status',
            'Pending'
        )
        ->latest()
        ->get();

        foreach ($approvalNotifications as $notification) {

            $notification->notification_type = 'approval';
            $notification->notification_priority = 1;

            $notifications->push($notification);

        }

    }

    /*
|--------------------------------------------------------------------------
| Assigned to You Notifications
|--------------------------------------------------------------------------
*/

if (auth()->check()) {

    $assignedNotifications = \App\Models\DocumentRequest::where(
    'assigned_to',
    auth()->id()
)
->where('status', 'Assigned')
->whereNotIn('approval_status', [
    'Declined',
    'Cancelled',
])
->latest('assigned_at')
->get();

    foreach ($assignedNotifications as $notification) {

        $notification->notification_type = 'assigned';
        $notification->notification_priority = 2;

        $notifications->push($notification);

    }

}

/*
|--------------------------------------------------------------------------
| Ongoing Notifications
|--------------------------------------------------------------------------
*/

if (auth()->check()) {

    $ongoingNotifications = \App\Models\DocumentRequest::where(
        'assigned_to',
        auth()->id()
    )
    ->where('status', 'Ongoing')
    ->whereNotIn('approval_status', [
        'Declined',
        'Cancelled',
    ])
    ->latest('assigned_at')
    ->get();

    foreach ($ongoingNotifications as $notification) {

        $notification->notification_type = 'ongoing';
        $notification->notification_priority = 2;

        $notifications->push($notification);

    }

}

    /*
    |--------------------------------------------------------------------------
    | Due Date Notifications
    |--------------------------------------------------------------------------
    */

    $dueNotifications = \App\Models\DocumentRequest::whereNotIn('status', [
        'Completed',
        'Archived',
    ])
    ->whereNotIn('approval_status', [
        'Declined',
        'Cancelled',
    ])
    ->whereNotNull('due_date')
    ->whereDate('due_date', '<=', today()->addDays(3))
    ->get();


    foreach ($dueNotifications as $notification) {

        $dueDate = \Carbon\Carbon::parse($notification->due_date)
            ->startOfDay();

        $today = \Carbon\Carbon::today();

        $daysUntilDue = $today->diffInDays($dueDate, false);

        $notification->days_until_due = $daysUntilDue;
        $notification->notification_type = 'due';


        if ($daysUntilDue < 0) {

            $notification->notification_priority = 3;

        } elseif ($daysUntilDue === 0) {

            $notification->notification_priority = 4;

        } else {

            $notification->notification_priority = 5;

        }

        $notifications->push($notification);

    }


    /*
    |--------------------------------------------------------------------------
    | Sort and Limit to Top 5
    |--------------------------------------------------------------------------
    */

    $notifications = $notifications
        ->sortBy([
            ['notification_priority', 'asc'],
            ['due_date', 'asc'],
        ])
        ->take(5);

@endphp


{{-- Notifications --}}



    @foreach($notifications as $notification)

    @if($notification->notification_type === 'approval')

        {{-- For Approval --}}

        <a
            href="{{ route('request-approvals.index') }}"
            class="dropdown-item px-3 py-3 border-bottom notification-item notification-approval">

            <div class="d-flex align-items-start">

                <div class="me-3">
                    <i class="bi bi-check-circle-fill text-primary fs-5"></i>
                </div>

                <div class="flex-grow-1">

                    <div class="fw-semibold">
                        {{ $notification->control_no }}
                    </div>

                    <div class="small text-muted">
                        {{ $notification->subject ?? 'No subject' }}
                    </div>

                    <div class="mt-1">

                        <span class="badge bg-primary">
                            <i class="bi bi-hourglass-split me-1"></i>
                            For Approval
                        </span>

                    </div>

                </div>

            </div>

        </a>


    @elseif($notification->notification_type === 'assigned')

        {{-- Assigned to You --}}

        <a
            href="{{ route('requests.show', $notification) }}"
            class="dropdown-item px-3 py-3 border-bottom notification-item notification-assigned">

            <div class="d-flex align-items-start">

                <div class="me-3">
                    <i class="bi bi-person-check-fill text-info fs-5"></i>
                </div>

                <div class="flex-grow-1">

                    <div class="fw-semibold">
                        {{ $notification->control_no }}
                    </div>

                    <div class="small text-muted">
                        {{ $notification->subject ?? 'No subject' }}
                    </div>

                    <div class="mt-1">

                        <span class="badge bg-info text-dark">
                            <i class="bi bi-person-check-fill me-1"></i>
                            Assigned to You
                        </span>

                    </div>

                </div>

            </div>

        </a>

@elseif($notification->notification_type === 'ongoing')

    {{-- Ongoing --}}

    <a
        href="{{ route('requests.show', $notification) }}"
        class="dropdown-item px-3 py-3 border-bottom notification-item notification-ongoing">

        <div class="d-flex align-items-start">

            <div class="me-3">
                <i class="bi bi-arrow-repeat text-purple fs-5"></i>
            </div>

            <div class="flex-grow-1">

                <div class="fw-semibold">
                    {{ $notification->control_no }}
                </div>

                <div class="small text-muted">
                    {{ $notification->subject ?? 'No subject' }}
                </div>

                <div class="mt-1">

                    <span class="badge notification-ongoing-badge">
                        <i class="bi bi-arrow-repeat me-1"></i>
                        Ongoing — Assigned to You
                    </span>

                </div>

            </div>

        </div>

    </a>

    @else

        {{-- Due Date Notification --}}

        @php
            $daysUntilDue = $notification->days_until_due;
        @endphp

        <a
            href="{{ route('requests.show', $notification) }}"
            class="dropdown-item px-3 py-3 border-bottom notification-item
                @if($daysUntilDue < 0)
                    notification-overdue
                @elseif($daysUntilDue === 0)
                    notification-today
                @else
                    notification-soon
                @endif">

            <div class="d-flex align-items-start">

                <div class="me-3">

                    @if($daysUntilDue < 0)

                        <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>

                    @elseif($daysUntilDue === 0)

                        <i class="bi bi-exclamation-circle-fill text-warning fs-5"></i>

                    @else

                        <i class="bi bi-clock-fill text-success fs-5"></i>

                    @endif

                </div>

                <div class="flex-grow-1">

                    <div class="fw-semibold">
                        {{ $notification->control_no }}
                    </div>

                    <div class="small text-muted">
                        {{ $notification->subject ?? 'No subject' }}
                    </div>

                    <div class="mt-1">

                        @if($daysUntilDue < 0)

                            <span class="badge bg-danger">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                Overdue by {{ abs($daysUntilDue) }}
                                {{ abs($daysUntilDue) === 1 ? 'day' : 'days' }}
                            </span>

                        @elseif($daysUntilDue === 0)

                            <span class="badge bg-warning text-dark">
                                <i class="bi bi-exclamation-circle-fill me-1"></i>
                                Due Today
                            </span>

                        @else

                            <span class="badge bg-success">
                                <i class="bi bi-clock-fill me-1"></i>
                                Due in {{ $daysUntilDue }}
                                {{ $daysUntilDue === 1 ? 'day' : 'days' }}
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </a>

    @endif

@endforeach

           

                
           

            <div class="border-top text-center">

    <a
        href="{{ route('requests.index') }}"
        class="dropdown-item py-2 text-primary fw-semibold">

        <i class="bi bi-list-ul me-1"></i>
        View All Requests

    </a>

</div>

        @else

            <div class="text-center py-4">

                <i class="bi bi-check-circle text-success fs-3"></i>

                <div class="mt-2 fw-semibold">

                    No notifications

                </div>

                <small class="text-muted">

                    You're all caught up.

                </small>

            </div>

        @endif

    </div>

</div>
        {{-- User Dropdown --}}
        <div class="dropdown">

            <button
                class="btn btn-light dropdown-toggle d-flex align-items-center"
                data-bs-toggle="dropdown">

                <i class="bi bi-person-circle me-2 fs-5"></i>

                {{ Auth::user()->name }}

            </button>

            <ul class="dropdown-menu dropdown-menu-end shadow">

                <li>

                    <h6 class="dropdown-header">

                        {{ Auth::user()->name }}

                    </h6>

                </li>

                <li>

                    <a
                        class="dropdown-item"
                        href="{{ route('profile.edit') }}">

                        <i class="bi bi-person me-2"></i>

                        My Profile

                    </a>

                </li>

                <li>

                    <a
                        class="dropdown-item"
                        href="#">

                        <i class="bi bi-key me-2"></i>

                        Change Password

                    </a>

                </li>

                <li>

                    <hr class="dropdown-divider">

                </li>

                <li>

                    <form
                        method="POST"
                        action="{{ route('logout') }}">

                        @csrf

                        <button
                            class="dropdown-item text-danger">

                            <i class="bi bi-box-arrow-right me-2"></i>

                            Logout

                        </button>

                    </form>

                </li>

            </ul>

        </div>

    </div>

</nav>