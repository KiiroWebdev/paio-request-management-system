<?php

namespace App\Actions\Requests;

use App\Models\Service;

class BuildValidationRules
{
    /**
     * Validation rules for each field type.
     */
    protected array $typeRules = [

        'text' => [
            'string',
            'max:255',
        ],

        'textarea' => [
            'string',
        ],

        'number' => [
            'numeric',
        ],

        'date' => [
            'date',
        ],

        'time' => [
            'date_format:H:i',
        ],

        'select' => [
            'string',
        ],

        'checkbox' => [
            'array',
        ],

        'file' => [
            'file',
        ],

    ];

    /**
     * Build validation rules for a service.
     */
    public function execute(Service $service): array
    {
        $service->loadMissing([
            'fields.fieldType',
        ]);

        $rules = [

            /*
            |--------------------------------------------------------------------------
            | Static Request Fields
            |--------------------------------------------------------------------------
            */

            'title' => ['required', 'string', 'max:255'],

            'subject' => ['required', 'string', 'max:255'],

            'description' => ['nullable', 'string'],

            'request_type' => ['required', 'in:Internal,External'],

            'organization_id' => ['nullable', 'exists:organizations,id'],

            'contact_id' => ['nullable', 'exists:contacts,id'],

            'priority' => ['required', 'in:Low,Normal,High,Urgent'],

            'answers' => ['array'],

        ];

        /*
        |--------------------------------------------------------------------------
        | Dynamic Service Fields
        |--------------------------------------------------------------------------
        */

        foreach ($service->fields as $field) {

            $fieldRules = [];

            // Required / Nullable
            $fieldRules[] = $field->required
                ? 'required'
                : 'nullable';

            // Add rules based on field type
            $fieldRules = array_merge(
                $fieldRules,
                $this->typeRules[$field->fieldType->key] ?? $this->typeRules['text']
            );

            // Save rules for this field
            $rules["answers.{$field->id}"] = $fieldRules;
        }

        return $rules;
    }
}