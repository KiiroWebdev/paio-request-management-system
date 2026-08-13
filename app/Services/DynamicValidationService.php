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

            $rule[] = $field->required
                ? 'required'
                : 'nullable';

            switch ($field->fieldType->key) {

                case 'text':
                    $rule[] = 'string';
                    break;

                case 'textarea':
                    $rule[] = 'string';
                    break;

                case 'number':
                    $rule[] = 'numeric';
                    break;

                case 'date':
                    $rule[] = 'date';
                    break;

                case 'time':
                    $rule[] = 'date_format:H:i';
                    break;

                case 'select':
                    $rule[] = 'string';
                    break;

                case 'checkbox':
                    $rule[] = 'array';
                    break;

                case 'file':
                    $rule[] = 'file';
                    break;

                default:
                    $rule[] = 'string';
                    break;
            }

            $rules[$field->field_name] = implode('|', $rule);
        }

        return $rules;
    }
}
