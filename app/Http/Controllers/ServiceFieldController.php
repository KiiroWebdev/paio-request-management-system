<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceField;
use Illuminate\Http\Request;

class ServiceFieldController extends Controller
{
    /**
     * Display a listing of the service fields.
     */
    public function index(Service $service)
    {
        $service->load([
            'fields.fieldType',
            'fields.options',
        ]);

        return view('services.fields.index', compact('service'));
    }

    /**
     * Show the form for creating a new field.
     */
    public function create(Service $service)
    {
        //
    }

    /**
     * Store a newly created field.
     */
    public function store(Request $request, Service $service)
    {
        //
    }

    /**
     * Display the specified field.
     */
    public function show(Service $service, ServiceField $field)
    {
        //
    }

    /**
     * Show the form for editing the specified field.
     */
    public function edit(Service $service, ServiceField $field)
    {
        //
    }

    /**
     * Update the specified field.
     */
    public function update(Request $request, Service $service, ServiceField $field)
    {
        //
    }

    /**
     * Remove the specified field.
     */
    public function destroy(Service $service, ServiceField $field)
    {
        //
    }
}