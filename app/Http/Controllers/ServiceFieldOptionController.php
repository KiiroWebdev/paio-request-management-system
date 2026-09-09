<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceField;
use App\Models\ServiceFieldOption;

use App\Http\Requests\StoreServiceFieldOptionRequest;
use App\Http\Requests\UpdateServiceFieldOptionRequest;

class ServiceFieldOptionController extends Controller
{
    /**
     * Display all options for a field.
     */
    public function index(Service $service, ServiceField $field)
{
    $field->load('options');

    return view(
        'services.fields.options.index',
        compact('service', 'field')
    );
}

/**
 * Show the create option page.
 */
public function create(
    Service $service,
    ServiceField $field
) {
    return view(
        'services.fields.options.create',
        compact('service', 'field')
    );
}
    /**
     * Store a new option.
     */
    public function store(
        StoreServiceFieldOptionRequest $request,
        Service $service,
        ServiceField $field
    ) {
        $nextOrder = ($field->options()->max('display_order') ?? 0) + 1;

        ServiceFieldOption::create([
            'service_field_id' => $field->id,
            'label'            => $request->label,
            'value'            => $request->value,
            'display_order'    => $nextOrder,
            'active'           => true,
        ]);

        return redirect()
            ->route('services.fields.options.index', [$service, $field])
            ->with('success', 'Option created successfully.');
    }
    /**
 * Show the edit option page.
 */
public function edit(
    Service $service,
    ServiceField $field,
    ServiceFieldOption $option
)
{
    return view(
    'services.fields.options.edit',
    compact(
        'service',
        'field',
        'option'
    )
);
}

    /**
     * Update an option.
     */
    public function update(
        UpdateServiceFieldOptionRequest $request,
        Service $service,
        ServiceField $field,
        ServiceFieldOption $option
    ) {
        $option->update([
            'label'  => $request->label,
            'value'  => $request->value,
            'active' => $request->boolean('active'),
        ]);

        return redirect()
            ->route('services.fields.options.index', [$service, $field])
            ->with('success', 'Option updated successfully.');
    }

    /**
     * Delete an option.
     */
    public function destroy(
        Service $service,
        ServiceField $field,
        ServiceFieldOption $option
    ) {
        $option->delete();

        return redirect()
            ->route('services.fields.options.index', [$service, $field])
            ->with('success', 'Option deleted successfully.');
    }
}