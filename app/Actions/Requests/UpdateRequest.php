<?php

namespace App\Actions\Requests;

use App\Models\DocumentRequest;

class UpdateRequest
{
    public function execute(
        DocumentRequest $request,
        array $data
    ): DocumentRequest {

        $request->update([

            'date_received' => $data['date_received'],

            'received_by' => $data['received_by'] ?? null,

            'request_type' => $data['request_type'],

            'has_request_letter' => $data['has_request_letter'] ?? false,

            'title' => $data['title'],

            'organization_id' => $data['organization_id'] ?? null,

            'contact_id' => $data['contact_id'] ?? null,

            'subject' => $data['subject'],

            'description' => $data['description'] ?? null,

            'priority' => $data['priority'] ?? 'Normal',

            'due_date' => $data['due_date'] ?? null,

        ]);

        return $request->fresh();

    }
}
