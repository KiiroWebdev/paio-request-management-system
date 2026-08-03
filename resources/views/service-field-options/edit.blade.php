@extends('layouts.master')

@section('page-title', 'Edit Option')

@section('content')

<div class="container-fluid">

    <h2 class="mb-1">

        Edit Option

    </h2>

    <p class="text-muted">

        Field: <strong>{{ $field->label }}</strong>

    </p>

    <form
        method="POST"
        action="{{ route('services.fields.options.update', [$service, $field, $option]) }}">

        @csrf
        @method('PUT')

        @include('service-field-options.form')

        <div class="mt-3">

            <button class="btn btn-primary">

                Save Changes

            </button>

            <a
                href="{{ route('services.fields.options.index', [$service, $field]) }}"
                class="btn btn-secondary">

                Cancel

            </a>

        </div>

    </form>

</div>

@endsection