<?php

namespace App\Services;

use App\Models\Service;

class DynamicValidationService
{
    public function rules(Service $service): array
    {
        $rules = [];

        foreach ($service->fields as $field) {

            $rule = [];

            $rule[] = $field->required ? 'required' : 'nullable';

            switch ($field->fieldType->key) {

                case 'Text':
                    $rule[] = 'string';
                    break;

                case 'Textarea':
                    $rule[] = 'string';
                    break;

                case 'Number':
                    $rule[] = 'numeric';
                    break;

                case 'Date':
                    $rule[] = 'date';
                    break;

                case 'Time':
                    $rule[] = 'date_format:H:i';
                    break;

                case 'File':
                    $rule[] = 'file';
                    break;

                default:
                    $rule[] = 'string';
            }

            $rules[$field->field_name] = implode('|', $rule);
        }

        return $rules;
    }
}