<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceFieldRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'label' => ['required', 'string', 'max:255'],

            'field_name' => ['required', 'string', 'max:255'],

            'field_type_id' => ['required', 'exists:field_types,id'],

            'placeholder' => ['nullable', 'string'],

            'help_text' => ['nullable', 'string'],

            // Removed display_order because it is generated automatically

            'required' => ['nullable', 'boolean'],

            'active' => ['nullable', 'boolean'],

        ];
    }
}