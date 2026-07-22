@extends('layouts.master')

@section('content')

<div class="container-fluid">

    <h2 class="mb-4">
        Dashboard
    </h2>

    <div class="row g-4">

    <x-stat-card
        title="Incoming"
        value="25"
        icon="bi-inbox"/>

    <x-stat-card
        title="Ongoing"
        value="12"
        icon="bi-hourglass-split"/>

    <x-stat-card
        title="Done"
        value="180"
        icon="bi-check-circle"/>

    <x-stat-card
        title="Archive"
        value="520"
        icon="bi-archive"/>

    <x-stat-card
        title="Users"
        value="8"
        icon="bi-people"/>

    <x-stat-card
        title="Departments"
        value="14"
        icon="bi-building"/>

</div>

</div>

@endsection