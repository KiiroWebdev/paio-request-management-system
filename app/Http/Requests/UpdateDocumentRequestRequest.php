<?php

namespace App\Http\Requests;

use App\Models\DocumentRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDocumentRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var DocumentRequest $documentRequest */
        $documentRequest = $this->route('documentRequest');
        $documentRequest->loadMissing([
            'services.fields.fieldType',
            'answers',
        ]);

        $rules = [
            'organization_id' => ['required', 'exists:organizations,id'],
            'contact_id' => [
                'required',
                Rule::exists('contacts', 'id')->where(
                    fn ($query) => $query->where('organization_id', $this->input('organization_id'))
                ),
            ],
            'title' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'date_received' => ['required', 'date'],
            'request_type' => ['required', Rule::in(['Internal', 'External'])],
            'priority' => ['required', Rule::in(['Low', 'Normal', 'High', 'Urgent'])],
            'answers' => ['nullable', 'array'],
        ];

        foreach ($documentRequest->services as $service) {
            foreach ($service->fields->where('active', true) as $field) {
                $key = "answers.{$field->id}";
                // Existing required files do not need to be uploaded again.
                $isExistingFile = $field->fieldType->key === 'file'
                    && $documentRequest->answers->contains('service_field_id', $field->id);

                $rules[$key] = [
                    $field->required && ! $isExistingFile ? 'required' : 'nullable',
                ];

                $rules[$key] = match ($field->fieldType->key) {
    'text' => [...$rules[$key], 'string', 'max:255'],
    'textarea' => [...$rules[$key], 'string'],
    'number' => [...$rules[$key], 'numeric'],
    'date' => [...$rules[$key], 'date'],
    'time' => [...$rules[$key], 'date_format:H:i'],
    'select' => [...$rules[$key], 'string'],
    'checkbox' => [...$rules[$key], 'array'],
    'file' => [...$rules[$key], 'file'],
    'schedule' => [...$rules[$key], 'array', 'min:1'],
    default => [...$rules[$key], 'string'],
};

if ($field->fieldType->key === 'schedule') {

    $rules["{$key}.*.date"] = [
        'required',
        'date',
    ];

    $rules["{$key}.*.start_time"] = [
        'required',
        'date_format:H:i',
    ];

    $rules["{$key}.*.end_time"] = [
        'required',
        'date_format:H:i',
    ];
}
            }
        }

        return $rules;
    }
}
