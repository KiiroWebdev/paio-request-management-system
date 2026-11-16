<?php

namespace App\Http\Controllers;

use App\Services\ControlNumberService;
use App\Models\Service;
use App\Http\Requests\StoreRequestRequest;
use App\Services\RequestSubmissionService;
use App\Models\Organization;
use App\Models\Contact;
use Illuminate\Support\Facades\DB;

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

    $services = Service::where('is_active', true)
        ->with(['fields.fieldType', 'fields.options'])
        ->orderBy('sort_order')
        ->get();

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
            'services',
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
    ControlNumberService $controlNumberService,
    RequestSubmissionService $submissionService
) {
    $data = $request->validated();

    $serviceIds = array_map('intval', $data['services']);

    if (! in_array($service->id, $serviceIds, true)) {
        return redirect()->back()->withErrors([
            'services' => 'Please include the primary service in your selection.',
        ])->withInput();
    }

    // A new organization requires a new contact.
    if (
        $data['manual_organization'] &&
        ! $data['manual_contact']
    ) {
        return redirect()->back()->withErrors([
            'contact_id' => 'Please enter the contact person for the new organization.',
        ])->withInput();
    }

    $documentRequest = DB::transaction(function () use (
        $data,
        $controlNumberService,
        $submissionService
    ) {
        // Create a new organization if manual entry was selected.
        if ($data['manual_organization']) {
            $organization = Organization::create([
                'name' => $data['new_organization_name'],
                'short_name' => $data['new_organization_short_name'] ?? null,
                'type' => $data['new_organization_type'] ?? null,
                'telephone' => $data['new_organization_telephone'] ?? null,
                'address' => $data['new_organization_address'] ?? null,
                'email' => $data['new_organization_email'] ?? null,
                'website' => $data['new_organization_website'] ?? null,
                'is_internal' => false,
                'is_active' => true,
            ]);

            $data['organization_id'] = $organization->id;
        }

       // Create a new contact if manual entry was selected.
if ($data['manual_contact']) {

    $contact = Contact::create([
        'organization_id' => $data['organization_id'],
        'first_name' => $data['new_contact_first_name'],
        'middle_name' => $data['new_contact_middle_name'] ?? null,
        'last_name' => $data['new_contact_last_name'],
        'suffix' => $data['new_contact_suffix'] ?? null,
        'position' => $data['new_contact_position'],
        'office' => $data['new_contact_office'] ?? null,
        'email' => $data['new_contact_email'] ?? null,
        'mobile' => $data['new_contact_mobile'] ?? null,
        'telephone' => $data['new_contact_telephone'] ?? null,
        'is_primary' => false,
        'is_active' => true,
    ]);

    $data['contact_id'] = $contact->id;
}
        $data['control_no'] = $controlNumberService->generate();
        $data['services'] = array_map('intval', $data['services']);

        return $submissionService->submit($data);
    });

    return redirect()->back()->with(
        'success',
        'Request submitted successfully! Control No.: ' . $documentRequest->control_no
    );
}


}