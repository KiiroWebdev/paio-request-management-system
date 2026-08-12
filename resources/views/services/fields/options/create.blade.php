@extends('layouts.master')

@section('content')

<div class="container-fluid">

    <x-admin.page-header
        title="Add Service"
        subtitle="Create a new PAIO service" />

    <x-admin.form-card>

        <form
            action="{{ route('services.store') }}"
            method="POST">

            @csrf

            @include('services.form')

        </form>

    </x-admin.form-card>

</div>

@endsection