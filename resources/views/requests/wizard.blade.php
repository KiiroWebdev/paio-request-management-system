@extends('layouts.master')

@section('page-title', 'New Request')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <x-admin.page-header
        title="New Document Request"
        subtitle="Complete the steps below to create a request">

        <a href="{{ route('requests.index') }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left"></i>

            Back to Requests

        </a>

    </x-admin.page-header>

    {{-- Breadcrumb --}}
    <x-admin.breadcrumb
        :items="['Request Management', 'New Request']"/>

    <form
    id="requestWizard"
    method="POST"
    action="{{ route('requests.store') }}"
    novalidate>
        @csrf

        <div class="card shadow-sm border-0">

            <div class="card-body">

                {{-- Wizard Progress --}}
                <div class="wizard-progress mb-5">

                    <div class="progress mb-4" style="height:6px;">

                        <div
                            id="wizardProgress"
                            class="progress-bar bg-primary"
                            style="width:14%;">

                        </div>

                    </div>

                    <div class="wizard-steps">

                        <div class="wizard-step active">
                            <div class="circle">1</div>
                            <small>Services</small>
                        </div>

                        <div class="wizard-step">
                            <div class="circle">2</div>
                            <small>Request</small>
                        </div>

                        <div class="wizard-step">
                            <div class="circle">3</div>
                            <small>Organization</small>
                        </div>

                        <div class="wizard-step">
                            <div class="circle">4</div>
                            <small>Contact</small>
                        </div>

                        <div class="wizard-step">
                            <div class="circle">5</div>
                            <small>Schedule</small>
                        </div>

                        <div class="wizard-step">
                            <div class="circle">6</div>
                            <small>Attachments</small>
                        </div>

                        <div class="wizard-step">
                            <div class="circle">7</div>
                            <small>Review</small>
                        </div>

                    </div>

                </div>

                {{-- STEP 1 --}}
                @include('requests.wizard.step1-services')

                {{-- STEP 2 --}}
                @include('requests.wizard.step2-request')

                {{-- STEP 3 --}}
                @include('requests.wizard.step3-organization')

                {{-- STEP 4 --}}
                @include('requests.wizard.step4-contact')

                {{-- STEP 5 --}}
                @include('requests.wizard.step5-schedule')

                {{-- STEP 6 --}}
                @include('requests.wizard.step6-attachments')

                {{-- STEP 7 --}}
                @include('requests.wizard.step7-review')

            </div>

            <div class="card-footer bg-white">

                <div class="d-flex justify-content-between">

                    <button
                        type="button"
                        id="prevBtn"
                        class="btn btn-secondary">

                        <i class="bi bi-arrow-left"></i>

                        Previous

                    </button>

                    <button
                        type="button"
                        id="nextBtn"
                        class="btn btn-primary">

                        Next

                        <i class="bi bi-arrow-right"></i>

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection