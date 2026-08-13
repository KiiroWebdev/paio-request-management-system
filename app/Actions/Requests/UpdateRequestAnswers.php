<?php

namespace App\Actions\Requests;

use App\Models\DocumentRequest;
use App\Models\ServiceField;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateRequestAnswers
{
    public function execute(
        DocumentRequest $request,
        array $answers,
        iterable $fields
    ): void {
        $existingAnswers = $request->answers()
            ->get()
            ->keyBy('service_field_id');

        foreach ($fields as $field) {
            /** @var ServiceField $field */
            $fieldId = $field->id;
            $value = $answers[$fieldId] ?? null;
            $existingAnswer = $existingAnswers->get($fieldId);

            if ($field->fieldType->key === 'file') {
                // An empty file input means "keep the current file".
                if (! $value instanceof UploadedFile) {
                    continue;
                }

                $path = $value->store("requests/{$request->id}/fields/{$fieldId}", 'public');

                if ($existingAnswer?->value) {
                    DB::afterCommit(fn () => Storage::disk('public')->delete($existingAnswer->value));
                }

                $request->answers()->updateOrCreate(
                    ['service_field_id' => $fieldId],
                    ['value' => $path]
                );

                continue;
            }

            // Checkboxes are omitted by browsers when nothing is checked.
            if ($value === null || $value === '' || $value === []) {
                $existingAnswer?->delete();

                continue;
            }

            $request->answers()->updateOrCreate(
                ['service_field_id' => $fieldId],
                [
                    'value' => is_array($value)
                        ? json_encode($value, JSON_UNESCAPED_UNICODE)
                        : $value,
                ]
            );
        }
    }
}
