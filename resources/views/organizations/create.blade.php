@extends('layouts.master')

@section('content')

<div class="container-fluid">

    <x-admin.page-header
        title="Add Organization"
        subtitle="Create a new organization" />

    <x-admin.form-card>

        <form
            action="{{ route('organizations.store') }}"
            method="POST">

            @csrf

            @include('organizations.form')

        </form>

    </x-admin.form-card>

</div>

@endsection