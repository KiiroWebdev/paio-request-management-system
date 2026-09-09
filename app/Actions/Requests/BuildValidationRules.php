<?php

namespace App\Actions\Requests;

use App\Models\Service;

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
        'schedule' => ['array', 'min:1'],
    ];

    public function execute(Service $service): array
    {
        $service->loadMissing([
            'fields.fieldType',
        ]);

        $rules = [
            'answers' => ['array'],
        ];

        foreach ($service->fields as $field) {

            $fieldType = $field->fieldType->key;

            $fieldRules = [];

            $fieldRules[] = $field->required
                ? 'required'
                : 'nullable';

            /*
             * Schedule fields are arrays.
             * All other fields use their normal type rules.
             */
            if ($fieldType === 'schedule') {

                $fieldRules[] = 'array';
                $fieldRules[] = 'min:1';

            } else {

                $fieldRules = array_merge(
                    $fieldRules,
                    $this->typeRules[$fieldType]
                        ?? $this->typeRules['text']
                );
            }

            $rules["answers.{$field->id}"] = $fieldRules;

            /*
             * Validate each Schedule entry.
             */
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

        return $rules;
    }
}