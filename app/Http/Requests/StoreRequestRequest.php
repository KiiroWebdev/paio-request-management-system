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

            // Request Information
            'title' => ['required', 'string', 'max:255'],

            'subject' => ['required', 'string', 'max:255'],

            'description' => ['nullable', 'string'],

            'date_received' => ['required', 'date'],

            'request_type' => ['required', 'in:Internal,External'],

            'priority' => ['required', 'in:Low,Normal,High,Urgent'],

        ], $builder->execute($service));
    }

    public function messages(): array
    {
        return [

            'required' => 'This field is required.',

            'date' => 'Please enter a valid date.',

            'numeric' => 'Please enter a valid number.',

            'file' => 'Please upload a valid file.',

        ];
    }
}