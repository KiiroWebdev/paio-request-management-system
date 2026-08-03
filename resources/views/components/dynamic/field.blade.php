@props([
    'field',
    'name' => null,
    'value' => null,
])

@php
    $type = $field->fieldType->key;
@endphp

@switch($type)

    @case('text')

        <x-dynamic.text
            :field="$field"
            :name="$name"
            :value="$value" />

        @break

    @case('textarea')

        <x-dynamic.textarea
            :field="$field"
            :name="$name"
            :value="$value" />

        @break

    @case('number')

        <x-dynamic.number
            :field="$field"
            :name="$name"
            :value="$value" />

        @break

    @case('date')

        <x-dynamic.date
            :field="$field"
            :name="$name"
            :value="$value" />

        @break

    @case('time')

        <x-dynamic.time
            :field="$field"
            :name="$name"
            :value="$value" />

        @break

    @case('dropdown')

    <x-dynamic.select
        :field="$field"
        :name="$name"
        :value="$value" />

    @break

    @case('checkbox')

        <x-dynamic.checkbox
            :field="$field"
            :name="$name"
            :value="$value" />

        @break

    @case('file')

        <x-dynamic.file
            :field="$field"
            :name="$name" />

        @break

@endswitch