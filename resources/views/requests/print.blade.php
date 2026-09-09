<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        {{ $documentRequest->control_no }} - PAIO Request
    </title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f1f3f5;
            color: #111;
            margin: 0;
            padding: 30px;
            font-size: 13px;
        }

        /* =========================================================
           PRINT BUTTONS
        ========================================================= */

        .print-actions {
            max-width: 900px;
            margin: 0 auto 20px;
            display: flex;
            gap: 10px;
        }

        .print-actions button,
        .print-actions a {
            border: none;
            padding: 10px 18px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 13px;
        }

        .btn-print {
            background: #0d6efd;
            color: #fff;
        }

        .btn-back {
            background: #6c757d;
            color: #fff;
        }

        /* =========================================================
           PRINT DOCUMENT
        ========================================================= */

        .print-container {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            padding: 40px;
        }

        /* =========================================================
           HEADER / LETTERHEAD
        ========================================================= */

        .letterhead {
            text-align: center;
            border-bottom: 2px solid #222;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

       .header-row {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 25px;
    width: 100%;
}

.header-logo {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 auto;
}

.header-logo img {
    width: 65px;
    height: 65px;
    object-fit: contain;
}

.header-text {
    text-align: center;
    flex: 0 0 auto;
}

        .office-name {
            font-size: 19px;
            font-weight: bold;
        }

        .office-subtitle {
            font-size: 13px;
            margin-top: 3px;
        }

        .document-title {
            font-size: 20px;
            font-weight: bold;
            margin-top: 18px;
            text-transform: uppercase;
        }

        .control-number {
            font-size: 15px;
            font-weight: bold;
            margin-top: 5px;
        }

        /* =========================================================
           SECTIONS
        ========================================================= */

        .section {
            margin-top: 25px;
        }

        .section-title {
            background: #f1f1f1;
            border: 1px solid #ccc;
            padding: 8px 10px;
            font-weight: bold;
            font-size: 14px;
        }

        .section-body {
            border: 1px solid #ccc;
            border-top: none;
            padding: 15px;
        }

        /* =========================================================
           INFORMATION TABLE
        ========================================================= */

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 7px 5px;
            vertical-align: top;
        }

        td.label {
            width: 22%;
            font-weight: bold;
            color: #555;
        }

        td.value {
            width: 28%;
        }

        /* =========================================================
           STATUS
        ========================================================= */

        .status {
            display: inline-block;
            padding: 4px 9px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 11px;
        }

        .status-incoming {
            background: #ffc107;
        }

        .status-ongoing {
            background: #0d6efd;
            color: #fff;
        }

        .status-completed {
            background: #198754;
            color: #fff;
        }

        .status-archived {
            background: #6c757d;
            color: #fff;
        }

        .priority {
            font-weight: bold;
        }

        /* =========================================================
           DYNAMIC SERVICE ANSWERS
        ========================================================= */

        .answer-row {
            display: flex;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .answer-row:last-child {
            border-bottom: none;
        }

        .answer-label {
            width: 30%;
            font-weight: bold;
        }

        .answer-value {
            width: 70%;
        }

        .badge {
            display: inline-block;
            padding: 4px 7px;
            margin-right: 4px;
            margin-bottom: 3px;
            background: #0d6efd;
            color: #fff;
            border-radius: 3px;
            font-size: 11px;
        }

        /* =========================================================
           FOOTER
        ========================================================= */

        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #aaa;
            font-size: 11px;
            color: #666;
            display: flex;
            justify-content: space-between;
        }

        /* =========================================================
           PRINT SETTINGS
        ========================================================= */

        @media print {

            @page {
                size: A4;
                margin: 15mm;
            }

            body {
                background: #fff;
                padding: 0;
                font-size: 11px;
            }

            .print-actions {
                display: none;
            }

            .print-container {
                max-width: none;
                padding: 0;
            }

            .section {
                break-inside: avoid;
            }

            .section-title {
                background: #eee !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .badge,
            .status {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

        }

    </style>

</head>

<body>

{{-- =========================================================
     PRINT ACTIONS
========================================================= --}}

<div class="print-actions">

    <button
        type="button"
        class="btn-print"
        onclick="window.print()">

        🖨 Print Request

    </button>

    <a
        href="{{ route('requests.show', $documentRequest) }}"
        class="btn-back">

        ← Back

    </a>

</div>


{{-- =========================================================
     PRINT DOCUMENT
========================================================= --}}

<div class="print-container">


    {{-- =====================================================
         LETTERHEAD
    ====================================================== --}}

    <div class="letterhead">

        <div class="header-row">

            {{-- City Government Logo --}}

            <div class="header-logo">

                <img
                    src="{{ asset('images/city-logo.png') }}"
                    alt="City Government Logo">

            </div>


            {{-- Header Text --}}

            <div class="header-text">

                <div class="office-name">

                    CITY GOVERNMENT OF SAN PEDRO

                </div>

                <div class="office-subtitle">

                    San Pedro, Laguna

                </div>

                <div class="office-subtitle">

                    Public Affairs & Information Office

                </div>

            </div>


            {{-- PAIO Logo --}}

            <div class="header-logo">

                <img
                    src="{{ asset('images/paio-logo.png') }}"
                    alt="PAIO Logo">

            </div>

        </div>


        {{-- Document Title --}}

        <div class="document-title">

            PAIO SERVICE REQUEST

        </div>


        {{-- Control Number --}}

        <div class="control-number">

            {{ $documentRequest->control_no }}

        </div>

    </div>


    {{-- =====================================================
         REQUEST INFORMATION
    ====================================================== --}}

    <div class="section">

        <div class="section-title">

            REQUEST INFORMATION

        </div>

        <div class="section-body">

            <table>

                <tr>

                    <td class="label">
                        Date Received
                    </td>

                    <td class="value">

                        {{ $documentRequest->date_received?->format('F d, Y') ?? '-' }}

                    </td>


                    <td class="label">
                        Request Type
                    </td>

                    <td class="value">

                        {{ $documentRequest->request_type ?? '-' }}

                    </td>

                </tr>


                <tr>

                    <td class="label">
                        Organization
                    </td>

                    <td class="value">

                        {{ $documentRequest->organization?->name ?? '-' }}

                    </td>


                    <td class="label">
                        Requestor
                    </td>

                    <td class="value">

                        {{ $documentRequest->contact?->full_name ?? '-' }}

                    </td>

                </tr>


                <tr>

                    <td class="label">
                        Title
                    </td>

                    <td class="value">

                        {{ $documentRequest->title ?? '-' }}

                    </td>


                    <td class="label">
                        Priority
                    </td>

                    <td class="value priority">

                        {{ $documentRequest->priority ?? '-' }}

                    </td>

                </tr>


                <tr>

                    <td class="label">
                        Subject
                    </td>

                    <td colspan="3">

                        {{ $documentRequest->subject ?? '-' }}

                    </td>

                </tr>


                <tr>

                    <td class="label">
                        Description
                    </td>

                    <td colspan="3">

                        {{ $documentRequest->description ?? '-' }}

                    </td>

                </tr>

            </table>

        </div>

    </div>


    {{-- =====================================================
         WORKFLOW INFORMATION
    ====================================================== --}}

    <div class="section">

        <div class="section-title">

            WORKFLOW INFORMATION

        </div>

        <div class="section-body">

            @php

                $statusClass = match($documentRequest->status) {

                    'Incoming' => 'status-incoming',

                    'Ongoing' => 'status-ongoing',

                    'Completed' => 'status-completed',

                    'Archived' => 'status-archived',

                    default => '',

                };

            @endphp


            <table>

                <tr>

                    <td class="label">
                        Current Status
                    </td>

                    <td>

                        <span class="status {{ $statusClass }}">

                            @if($documentRequest->approval_status === 'Pending' && $documentRequest->status === 'Incoming')
    For Approval
@elseif($documentRequest->approval_status === 'Declined')
    Declined
@elseif($documentRequest->approval_status === 'Cancelled')
    Cancelled
@else
    {{ $documentRequest->status }}
@endif
                        </span>

                    </td>


                    <td class="label">
                        Due Date
                    </td>

                    <td>

                        {{ $documentRequest->due_date?->format('F d, Y') ?? '-' }}

                    </td>

                </tr>


                <tr>

                    <td class="label">
                        Assigned To
                    </td>

                    <td>

                        {{ $documentRequest->assignedTo?->name ?? 'Not Assigned' }}

                    </td>


                    <td class="label">
                        Assigned At
                    </td>

                    <td>

                        {{ optional($documentRequest->assigned_at)->format('F d, Y h:i A') ?? '-' }}

                    </td>

                </tr>


                @if($documentRequest->completed_at)

                    <tr>

                        <td class="label">
                            Completed At
                        </td>

                        <td colspan="3">

                            {{ $documentRequest->completed_at->format('F d, Y h:i A') }}

                        </td>

                    </tr>

                @endif


                @if($documentRequest->archived_at)

                    <tr>

                        <td class="label">
                            Archived At
                        </td>

                        <td colspan="3">

                            {{ $documentRequest->archived_at->format('F d, Y h:i A') }}

                        </td>

                    </tr>

                @endif

            </table>

        </div>

    </div>


    {{-- =====================================================
         SERVICE INFORMATION
    ====================================================== --}}

    <div class="section">

        <div class="section-title">

            SERVICE INFORMATION

        </div>

        <div class="section-body">

            @forelse($documentRequest->services as $service)

                <h4>

                    {{ $service->name }}

                </h4>


                @foreach($service->fields->where('active', true) as $field)

                    @php

                        $answer = $documentRequest->answers
                            ->firstWhere(
                                'service_field_id',
                                $field->id
                            );

                        $value = $answer?->value;

                    @endphp


                    <div class="answer-row">

                        <div class="answer-label">

                            {{ $field->label }}

                        </div>


                        <div class="answer-value">

                            @switch($field->fieldType->key)


                                {{-- DATE --}}

                                @case('date')

                                    {{ $value
                                        ? \Carbon\Carbon::parse($value)->format('F d, Y')
                                        : '-'
                                    }}

                                    @break


                                {{-- TIME --}}

@case('time')

    {{ $value
        ? \Carbon\Carbon::parse($value)->format('h:i A')
        : '-'
    }}

    @break


{{-- SCHEDULE --}}

@case('schedule')

    @php

        $schedules = $value
            ? json_decode($value, true)
            : [];

    @endphp

    @if(is_array($schedules) && count($schedules))

        @foreach($schedules as $schedule)

            <div style="margin-bottom: 8px;">

                <strong>
                    <i class="bi bi-calendar-event"></i>

                    @if(!empty($schedule['date']))
                        {{ \Carbon\Carbon::parse($schedule['date'])->format('F d, Y') }}
                    @else
                        -
                    @endif
                </strong>

                @if(!empty($schedule['start_time']) && !empty($schedule['end_time']))

                    <br>

                    <span>
                        <i class="bi bi-clock"></i>

                        {{ \Carbon\Carbon::parse($schedule['start_time'])->format('h:i A') }}

                        –

                        {{ \Carbon\Carbon::parse($schedule['end_time'])->format('h:i A') }}
                    </span>

                @endif

            </div>

        @endforeach

    @else

        -

    @endif

    @break


{{-- CHECKBOX --}}

                                @case('checkbox')

                                    @php

                                        $items = $value
                                            ? json_decode($value, true)
                                            : [];

                                    @endphp


                                    @if(is_array($items) && count($items))

                                        @foreach($items as $item)

                                            <span class="badge">

                                                {{ $item }}

                                            </span>

                                        @endforeach

                                    @else

                                        -

                                    @endif

                                    @break


                                {{-- SELECT --}}

                                @case('select')

                                    @php

                                        $option = $field->options
                                            ->firstWhere(
                                                'value',
                                                $value
                                            );

                                    @endphp

                                    {{ $option?->label ?? $value ?? '-' }}

                                    @break


                                {{-- FILE --}}

                                @case('file')

                                    {{ $value
                                        ? basename($value)
                                        : '-'
                                    }}

                                    @break


                                {{-- DEFAULT --}}

                                @default

                                    {{ $value ?: '-' }}

                            @endswitch

                        </div>

                    </div>

                @endforeach


            @empty

                <div>

                    No service information.

                </div>

            @endforelse

        </div>

    </div>


    {{-- =====================================================
         FOOTER
    ====================================================== --}}

    <div class="footer">

        <div>

            Control No.:
            {{ $documentRequest->control_no }}

        </div>

        <div>

            Printed:
            {{ now()->format('F d, Y h:i A') }}

        </div>

    </div>

</div>

</body>

</html>