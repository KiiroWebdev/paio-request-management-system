<?php

namespace App\Http\Requests;

use App\Actions\Requests\BuildValidationRules;
use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    
protected function prepareForValidation(): void
{
    $includeEventDate = $this->boolean('include_event_date');
    $includeEventSchedule = $this->boolean('include_event_schedule');

    $manualOrganization = $this->input('organization_id') === 'manual';
    $manualContact = $this->input('contact_id') === 'manual';

    $this->merge([
        // Manual Organization and Contact
        'manual_organization' => $manualOrganization,
        'manual_contact' => $manualContact,

        'organization_id' => $manualOrganization
            ? null
            : $this->input('organization_id'),

        'contact_id' => $manualContact
            ? null
            : $this->input('contact_id'),

        // Existing Event Information
        'event_date' => $includeEventDate
            ? $this->input('event_date')
            : null,

        'event_schedule' => $includeEventSchedule
            ? $this->input('event_schedule')
            : null,
    ]);
}


    public function rules(BuildValidationRules $builder): array
    {
        $routeService = $this->route('service');

        if (! $routeService instanceof Service) {
            $routeService = Service::where('slug', $routeService)
                ->firstOrFail();
        }

        $rules = [
            'services' => [
                'required',
                'array',
                'min:1',
            ],

            'services.*' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('services', 'id')
                    ->where('is_active', true),
            ],

            
            // Organization
            'manual_organization' => [
                'required',
                'boolean',
            ],

            'organization_id' => [
                'nullable',
                Rule::requiredIf(! $this->boolean('manual_organization')),
                'integer',
                'exists:organizations,id',
            ],

            'new_organization_name' => [
                Rule::requiredIf($this->boolean('manual_organization')),
                'nullable',
                'string',
                'max:255',
            ],

            'new_organization_short_name' => [
                'nullable',
                'string',
                'max:100',
            ],

           'new_organization_type' => [
    Rule::requiredIf($this->boolean('manual_organization')),
    'string',
    Rule::in([
        'Department',
        'Office',
        'Division',
        'Section',
        'Barangay',
        'National Agency',
        'GOCC',
        'School',
        'University',
        'Hospital',
        'Private Company',
        'NGO',
        'Peoples Organization',
        'Media',
        'Individual',
        'Other',
    ]),
],

            'new_organization_telephone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'new_organization_address' => [
                'nullable',
                'string',
            ],

            'new_organization_email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'new_organization_website' => [
                'nullable',
                'url',
                'max:255',
            ],

            // Contact / Requestor
            'manual_contact' => [
                'required',
                'boolean',
            ],

            'contact_id' => [
                'nullable',
                Rule::requiredIf(! $this->boolean('manual_contact')),
                'integer',
                Rule::exists('contacts', 'id')
                    ->where('organization_id', $this->input('organization_id')),
            ],

            'new_contact_first_name' => [
                Rule::requiredIf($this->boolean('manual_contact')),
                'nullable',
                'string',
                'max:100',
            ],

            'new_contact_middle_name' => [
                'nullable',
                'string',
                'max:100',
            ],

            'new_contact_last_name' => [
                Rule::requiredIf($this->boolean('manual_contact')),
                'nullable',
                'string',
                'max:100',
            ],

            'new_contact_suffix' => [
                'nullable',
                'string',
                'max:20',
            ],

           'new_contact_position' => [
    Rule::requiredIf($this->boolean('manual_contact')),
    'string',
    'max:150',
],

            'new_contact_office' => [
                'nullable',
                'string',
                'max:150',
            ],

            'new_contact_email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'new_contact_mobile' => [
                'nullable',
                'string',
                'max:30',
            ],

            'new_contact_telephone' => [
                'nullable',
                'string',
                'max:30',
            ],


            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'subject' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

           

'include_event_date' => [
    'sometimes',
    'boolean',
],

'include_event_schedule' => [
    'sometimes',
    'boolean',
],

'event_date' => [
    'nullable',
    'required_if:include_event_date,1',
    'date',
],

'event_schedule' => [
    'nullable',
    'required_if:include_event_schedule,1',
    'array',
    'min:1',
],

'event_schedule.*.date' => [
    'required',
    'date',
],

'event_schedule.*.start_time' => [
    'required',
    'date_format:H:i',
],

'event_schedule.*.end_time' => [
    'required',
    'date_format:H:i',
],

            'date_received' => [
                'required',
                'date',
            ],

            'due_date' => [
                'nullable',
                'date',
            ],

            'request_type' => [
                'required',
                'in:Internal,External',
            ],

            'priority' => [
                'required',
                'in:Low,Normal,High,Urgent',
            ],

            'attachments' => [
                'required',
                'array',
                'min:1',
            ],

            'attachments.*.attachment_type' => [
                'required',
                'in:Request Letter,Program,Layout,Memorandum,Photo,Video,Document,Others',
            ],

            'attachments.*.file' => [
                'required',
                'file',
                'max:20480',
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,webp,mp4,mov',
            ],
        ];

        // Load the fields belonging to the primary service only.
        $routeService->loadMissing([
            'fields.fieldType',
            'fields.options',
        ]);

        return array_merge(
            $rules,
            $builder->execute($routeService)
        );
    }
}