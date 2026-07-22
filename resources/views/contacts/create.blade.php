@extends('layouts.master')

@section('page-title', 'New Contact')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <x-admin.page-header
        title="New Contact"
        subtitle="Create a new contact">

        <a href="{{ route('contacts.index') }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left"></i>

            Back to Contacts

        </a>

    </x-admin.page-header>

    {{-- Breadcrumb --}}
    <x-admin.breadcrumb
        :items="['Master Data', 'Contacts', 'Create']"/>

    <form
        action="{{ route('contacts.store') }}"
        method="POST">

        @csrf

        @include('contacts.form')

        <div class="mt-4 d-flex justify-content-end gap-2">

            <a
                href="{{ route('contacts.index') }}"
                class="btn btn-secondary">

                Cancel

            </a>

            <button
                type="submit"
                class="btn btn-primary">

                <i class="bi bi-check-circle"></i>

                Save Contact

            </button>

        </div>

    </form>

</div>

@endsection