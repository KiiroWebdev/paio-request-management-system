@extends('layouts.master')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="mb-0">
            Incoming Requests
        </h2>

        <a href="{{ route('requests.create') }}"
           class="btn btn-primary">

            <i class="bi bi-plus-circle"></i>

            New Request

        </a>

    </div>

    <div class="card shadow-sm">

        <div class="card-body">

            <table class="table table-hover align-middle">

                <thead>

                    <tr>

                        <th>Control No.</th>
                        <th>Requester</th>
                        <th>Department</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Due Date</th>
                        <th width="180">Actions</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($requests as $request)

                    <tr>

                        <td>{{ $request->control_no }}</td>

                        <td>{{ $request->requester }}</td>

                        <td>{{ $request->department }}</td>

                        <td>{{ $request->subject }}</td>

                        <td>

                            <span class="badge bg-warning">

                                {{ $request->status }}

                            </span>

                        </td>

                        <td>

                            {{ $request->priority }}

                        </td>

                        <td>

                            {{ optional($request->due_date)->format('M d, Y') }}

                        </td>

                        <td>

                            <a href="{{ route('requests.show',$request) }}"
                               class="btn btn-sm btn-info">

                                <i class="bi bi-eye"></i>

                            </a>

                            <a href="{{ route('requests.edit',$request) }}"
                               class="btn btn-sm btn-success">

                                <i class="bi bi-pencil"></i>

                            </a>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="8"
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

@endsection