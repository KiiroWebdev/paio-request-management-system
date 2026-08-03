@extends('layouts.master')

@section('page-title', 'Add Field')

@section('content')

<div class="container-fluid">

    <x-admin.page-header
        title="Add Field"
        :subtitle="$service->name">

    </x-admin.page-header>

    <x-admin.breadcrumb
        :items="[
            'Master Data',
            'Services',
            $service->name,
            'Add Field'
        ]"/>

    <x-admin.form-card>

        <form
            action="{{ route('services.fields.store', $service) }}"
            method="POST">

            @include('services.fields.form')

        </form>

    </x-admin.form-card>

</div>

@endsection 