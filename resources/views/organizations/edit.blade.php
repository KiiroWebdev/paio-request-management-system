@extends('layouts.master')

@section('content')

<div class="container-fluid">

    <x-admin.page-header
        title="Edit Organization"
        subtitle="Update organization information" />

    <x-admin.form-card>

        <form
            action="{{ route('organizations.update', $organization) }}"
            method="POST">

            @csrf
            @method('PUT')

            @include('organizations.form')

        </form>

    </x-admin.form-card>

</div>

@endsection