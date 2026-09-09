<?php

namespace App\Http\Requests;

use App\Actions\Requests\BuildValidationRules;
use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(BuildValidationRules $builder): array
{
    $service = $this->route('service');

    if (! $service instanceof Service) {
        $service = Service::where('slug', $service)->firstOrFail();
    }

    return array_merge([

        /*
        |--------------------------------------------------------------------------
        | Request Information
        |--------------------------------------------------------------------------
        */

        

        'organization_id' => [
            'required',
            'exists:organizations,id',
        ],

        'contact_id' => [
            'required',
            'exists:contacts,id',
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
        'attachments' => ['required', 'array', 'min:1'],

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

    ], $builder->execute($service));
}
}