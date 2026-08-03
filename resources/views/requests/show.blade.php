@extends('layouts.master')

@section('page-title', 'Request Details')

@section('content')

<div class="container-fluid">

    <div class="card shadow-sm">

        <div class="card-header">

            <h3>

                {{ $documentRequest->title }}

            </h3>

            <small class="text-muted">

                Control No.

                <strong>

                    {{ $documentRequest->control_no }}

                </strong>

            </small>

        </div>

        <div class="card-body">

       <div class="row">

    <div class="col-md-6">

        <p>

            <strong>Subject</strong>

            <br>

            {{ $documentRequest->subject }}

        </p>

    </div>

    <div class="col-md-3">

        <p>

            <strong>Status</strong>

            <br>

            {{ $documentRequest->status }}

        </p>

    </div>

    <div class="col-md-3">

        <p>

            <strong>Priority</strong>

            <br>

            {{ $documentRequest->priority }}

        </p>

    </div>

</div>

<hr>

<h5>

    Service Information

</h5>

<table class="table">

    <tbody>

    @foreach($documentRequest->answers as $answer)

        <tr>

            <th width="250">

                {{ $answer->field->label }}

            </th>

            <td>

                @if(is_array($answer->formatted_value))

                    @foreach($answer->formatted_value as $item)

                        <span class="badge bg-primary">

                            {{ $item }}

                        </span>

                    @endforeach

                @else

                    {{ $answer->formatted_value }}

                @endif

            </td>

        </tr>

    @endforeach

    </tbody>

</table>

</div>

</div>

</div>

@endsection