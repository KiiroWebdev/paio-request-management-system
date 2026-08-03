<?php

namespace App\Actions\Requests;

use App\Models\DocumentRequest;

class AttachServices
{
    public function execute(
        DocumentRequest $request,
        array $services
    ): void {

        $request->services()->syncWithoutDetaching($services);

    }
}