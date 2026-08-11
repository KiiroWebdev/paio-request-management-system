@extends('layouts.master')

@section('page-title', 'Edit Field')

@section('content')

<div class="container">

    <div class="card shadow-sm">

        <div class="card-header">

            <h4 class="mb-0">

                Edit Field

            </h4>

        </div>

        <div class="card-body">

            <form
                action="{{ route('services.fields.update', [$service, $field]) }}"
                method="POST">

                @csrf
                @method('PUT')

                @include('services.fields.form')

            </form>

        </div>

    </div>

</div>

@endsection