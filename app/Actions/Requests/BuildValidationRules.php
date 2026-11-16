<?php

namespace App\Actions\Requests;

use App\Models\Service;
use Illuminate\Support\Collection;

class BuildValidationRules
{
    protected array $typeRules = [
        'text' => ['string', 'max:255'],
        'textarea' => ['string'],
        'number' => ['numeric'],
        'date' => ['date'],
        'time' => ['date_format:H:i'],
        'select' => ['string'],
        'checkbox' => ['array'],
        'file' => ['file'],
        'schedule' => ['array'],
    ];

    /**
     * Build validation rules for one or multiple services.
     */
    public function execute(Service|Collection $services): array
    {
        if ($services instanceof Service) {
            $services = collect([$services]);
        }

       $services->each(function (Service $service) {
    $service->loadMissing('fields.fieldType');
});

        $rules = [
            'answers' => ['nullable', 'array'],
        ];

        // These are now handled by shared request-level event fields.
        $sharedEventFieldNames = [
            'event_schedule',
            'date_of_event',
            'schedule_of_event',
        ];

        foreach ($services as $service) {
            foreach ($service->fields as $field) {

                // Skip old service-specific event fields.
                if (in_array(
                    strtolower($field->field_name),
                    $sharedEventFieldNames,
                    true
                )) {
                    continue;
                }

                $fieldType = $field->fieldType->key;

                $fieldRules = [
                    $field->required ? 'required' : 'nullable',
                ];

                $fieldRules = array_merge(
                    $fieldRules,
                    $this->typeRules[$fieldType]
                        ?? $this->typeRules['text']
                );

                $rules["answers.{$field->id}"] = $fieldRules;

                if ($fieldType === 'schedule') {
                    $rules["answers.{$field->id}.*.date"] = [
                        'required',
                        'date',
                    ];

                    $rules["answers.{$field->id}.*.start_time"] = [
                        'required',
                        'date_format:H:i',
                    ];

                    $rules["answers.{$field->id}.*.end_time"] = [
                        'required',
                        'date_format:H:i',
                    ];
                }
            }
        }

        return $rules;
    }
}