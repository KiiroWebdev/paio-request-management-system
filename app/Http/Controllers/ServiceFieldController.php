<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\FieldType;
use App\Models\ServiceField;
use App\Http\Requests\StoreServiceFieldRequest;
use App\Http\Requests\UpdateServiceFieldRequest;

class ServiceFieldController extends Controller
{
    /**
     * Display all fields for a service.
     */
    public function index(Service $service)
    {
        $service->load([
            'fields.fieldType',
        ]);

        $service->fields->loadCount('options');

        $fieldTypes = FieldType::orderBy('name')->get();

        return view(
            'service-fields.index',
            compact(
                'service',
                'fieldTypes'
            )
        );
    }

    /**
     * Show the create field page.
     */
    public function create(Service $service)
    {
        $fieldTypes = FieldType::orderBy('name')->get();

        return view(
            'service-fields.create',
            compact(
                'service',
                'fieldTypes'
            )
        );
    }

    /**
     * Store a newly created field.
     */
    public function store(
        StoreServiceFieldRequest $request,
        Service $service
    ) {
        $nextOrder = ServiceField::where('service_id', $service->id)
            ->max('display_order') + 1;

        ServiceField::create([
            'service_id'    => $service->id,
            'field_type_id' => $request->field_type_id,
            'field_name'    => $request->field_name,
            'label'         => $request->label,
            'placeholder'   => $request->placeholder,
            'help_text'     => $request->help_text,
            'display_order' => $nextOrder,
            'required'      => $request->boolean('required'),
            'active'        => $request->boolean('active'),
        ]);

        return redirect()
            ->route('services.fields.index', $service)
            ->with('success', 'Field created successfully.');
    }

    /**
     * Show the edit page.
     */
    public function edit(
        Service $service,
        ServiceField $field
    ) {
        $fieldTypes = FieldType::orderBy('name')->get();

        return view(
            'service-fields.edit',
            compact(
                'service',
                'field',
                'fieldTypes'
            )
        );
    }

    /**
     * Update the field.
     */
    public function update(
        UpdateServiceFieldRequest $request,
        Service $service,
        ServiceField $field
    ) {
        $field->update([
            'field_type_id' => $request->field_type_id,
            'field_name'    => $request->field_name,
            'label'         => $request->label,
            'placeholder'   => $request->placeholder,
            'help_text'     => $request->help_text,
            'required'      => $request->boolean('required'),
            'active'        => $request->boolean('active'),
        ]);

        return redirect()
            ->route('services.fields.index', $service)
            ->with('success', 'Field updated successfully.');
    }

    /**
     * Delete the field.
     */
    public function destroy(
        Service $service,
        ServiceField $field
    ) {
        $field->delete();

        return redirect()
            ->route('services.fields.index', $service)
            ->with('success', 'Field deleted successfully.');
    }
}