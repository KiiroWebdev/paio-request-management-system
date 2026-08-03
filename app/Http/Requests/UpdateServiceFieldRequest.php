<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceFieldRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [

            'label' => [
                'required',
                'string',
                'max:255',
            ],

            'field_name' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
            ],

            'field_type_id' => [
                'required',
                'exists:field_types,id',
            ],

            'placeholder' => [
                'nullable',
                'string',
                'max:255',
            ],

            'help_text' => [
                'nullable',
                'string',
            ],

            'required' => [
                'nullable',
                'boolean',
            ],

            'active' => [
                'nullable',
                'boolean',
            ],

        ];
    }

    /**
     * Custom attribute names.
     */
    public function attributes(): array
    {
        return [

            'field_name' => 'field name',
            'field_type_id' => 'field type',

        ];
    }
}