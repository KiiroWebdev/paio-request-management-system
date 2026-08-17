<?php

namespace App\Services;

use App\Models\DocumentRequest;

class RequestWorkflowService
{
    public function assign(
        DocumentRequest $request,
        int $userId
    ): void
    {
        $request->update([

            'assigned_to' => $userId,

            'assigned_at' => now(),

            'status' => 'Assigned',

        ]);
    }

    public function start(
        DocumentRequest $request
    ): void
    {
        $request->update([

            'status' => 'Ongoing',

        ]);
    }

    public function complete(
        DocumentRequest $request
    ): void
    {
        $request->update([

            'status' => 'Completed',

            'completed_at' => now(),

        ]);
    }

    public function archive(
        DocumentRequest $request
    ): void
    {
        $request->update([

            'status' => 'Archived',

            'archived_at' => now(),

        ]);
    }

    public function restore(
    DocumentRequest $request
): void
{
    $request->update([

        'status' => 'Incoming',

        'archived_at' => null,

    ]);
}
}