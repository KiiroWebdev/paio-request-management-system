<?php

namespace App\Actions\Requests;

use App\Models\DocumentRequest;

class SaveRequestAnswers
{
    public function execute(
        DocumentRequest $request,
        array $answers
    ): void {

        foreach ($answers as $fieldId => $value) {

            $request->answers()->create([

                'service_field_id' => $fieldId,

                'value' => is_array($value)
                ? json_encode($value, JSON_UNESCAPED_UNICODE)
                : $value,

            ]);

        }
    }
}