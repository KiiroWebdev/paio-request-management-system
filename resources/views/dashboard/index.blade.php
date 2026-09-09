@extends('layouts.master')

@section('content')

<div class="container-fluid">

    <h2 class="mb-4">
        Dashboard
    </h2>

    <div class="row g-4">

        {{-- Incoming --}}
        <x-stat-card
            title="Incoming"
            :value="$incomingCount"
            icon="bi-inbox"
            :href="route('requests.incoming')"
        />

        {{-- Ongoing --}}
        <x-stat-card
            title="Ongoing"
            :value="$ongoingCount"
            icon="bi-hourglass-split"
            :href="route('requests.ongoing')"
        />

        {{-- Completed --}}
        <x-stat-card
            title="Done"
            :value="$completedCount"
            icon="bi-check-circle"
            :href="route('requests.completed')"
        />

        {{-- Archived --}}
        <x-stat-card
            title="Archive"
            :value="$archivedCount"
            icon="bi-archive"
            :href="route('requests.archive.index')"
        />



    </div>
{{-- Monitoring --}}
<div class="row g-4 mt-1">

    {{-- High / Urgent --}}
    <x-stat-card
        title="High / Urgent"
        :value="$highPriorityCount"
        icon="bi-exclamation-triangle"
        :href="url('/requests?high_priority=1')"
    />

    {{-- Due Today --}}
<x-stat-card
    title="Due Today"
    :value="$dueTodayCount"
    icon="bi-calendar-check"
    :href="url('/requests?due_today=1')"
/>

{{-- Due Soon --}}
<x-stat-card
    title="Due Soon"
    :value="$dueSoonCount"
    icon="bi-calendar-event"
    :href="url('/requests?due_soon=1')"
/>

{{-- Overdue --}}
<x-stat-card
    title="Overdue"
    :value="$overdueCount"
    icon="bi-exclamation-circle"
    :href="url('/requests?overdue=1')"
/>

    {{-- Unassigned --}}
    <x-stat-card
        title="Unassigned"
        :value="$unassignedCount"
        icon="bi-person-x"
        :href="url('/requests?unassigned=1')"
    />

</div>
</div>

@endsection
