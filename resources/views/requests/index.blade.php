@extends('layouts.master')

@section('page-title', 'Incoming Requests')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="mb-0">
            Incoming Requests
        </h2>

        <a href="{{ route('request-wizard.services') }}"
           class="btn btn-primary">

            <i class="bi bi-plus-circle"></i>
            New Request

        </a>

    </div>

    <div class="card shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>

                        <tr>

                            <th>Control No.</th>
                            <th>Service</th>
                            <th>Requester</th>
                            <th>Department</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Due Date</th>
                            <th width="160">Actions</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($requests as $request)

                            <tr>

                                <td>

                                    <strong>
                                        {{ $request->control_no }}
                                    </strong>

                                </td>

                                <td>

                                    {{ $request->services->first()?->name ?? '-' }}

                                </td>

                                <td>

                                    {{ $request->contact?->full_name ?? '-' }}

                                </td>

                                <td>

                                    {{ $request->organization?->name ?? '-' }}

                                </td>

                                <td>

                                    {{ $request->subject }}

                                </td>

                                <td>

                                    @php
                                        $statusColor = match($request->status) {
                                            'Incoming' => 'warning',
                                            'Ongoing' => 'primary',
                                            'Completed' => 'success',
                                            'Archived' => 'secondary',
                                            default => 'dark',
                                        };
                                    @endphp

                                    <span class="badge bg-{{ $statusColor }}">

                                        {{ $request->status }}

                                    </span>

                                </td>

                                <td>

                                    @php
                                        $priorityColor = match($request->priority) {
                                            'Urgent' => 'danger',
                                            'High' => 'warning',
                                            'Normal' => 'primary',
                                            'Low' => 'secondary',
                                            default => 'secondary',
                                        };
                                    @endphp

                                    <span class="badge bg-{{ $priorityColor }}">

                                        {{ $request->priority }}

                                    </span>

                                </td>

                                <td>

                                    {{ optional($request->due_date)->format('M d, Y') ?? '-' }}

                                </td>

                                <td>

                                    <a href="{{ route('requests.show', $request) }}"
                                       class="btn btn-sm btn-info">

                                        <i class="bi bi-eye"></i>

                                    </a>

                                    <a href="{{ route('requests.edit', $request) }}"
                                       class="btn btn-sm btn-success">

                                        <i class="bi bi-pencil"></i>

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9"
                                    class="text-center text-muted py-5">

                                    No requests found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection