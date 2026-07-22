@extends('layouts.master')

@section('content')

<div class="container-fluid">

    <x-admin.page-header
        title="Edit Service"
        subtitle="Update service information" />

    <x-admin.form-card>

        <form
            action="{{ route('services.update', $service) }}"
            method="POST">

            @csrf

            @method('PUT')

            @include('services.form')

        </form>

    </x-admin.form-card>

</div>

@endsection