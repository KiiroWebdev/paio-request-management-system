<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Http\Requests\StoreRequestRequest;
use App\Services\RequestSubmissionService;
use App\Models\Organization;
use App\Models\Contact;

class RequestWizardController extends Controller
{
    /**
     * Show available services.
     */
    public function services()
{
    $services = Service::where('is_active', true)
        ->orderBy('sort_order')
        ->get();

    return view(
        'request-wizard.services',
        compact('services')
    );
}

    /**
     * Show the request form.
     */
    public function create(Service $service)
{
    $service->load([
        'fields.fieldType',
        'fields.options',
    ]);

    $organizations = Organization::where('is_active', true)
        ->orderBy('name')
        ->get();

    $contacts = Contact::where('is_active', true)
        ->orderBy('last_name')
        ->get();

    return view(
        'request-wizard.create',
        compact(
            'service',
            'organizations',
            'contacts'
        )
    );
}

    /**
     * Store a request.
     */
    public function store(
        StoreRequestRequest $request,
        Service $service,
        RequestSubmissionService $submissionService
    ) {
        

        $data = $request->validated();

        // Associate the selected service
        $data['services'] = [$service->id];

        // Submit the request
        $documentRequest = $submissionService->submit($data);

        return redirect()
            ->back()
            ->with(
                'success',
                'Request submitted successfully! Control No.: ' . $documentRequest->control_no
            );
    }
}