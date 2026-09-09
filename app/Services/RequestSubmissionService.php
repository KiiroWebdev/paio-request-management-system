<?php

namespace App\Services;

use App\Actions\Requests\AttachServices;
use App\Actions\Requests\CreateRequest;
use App\Actions\Requests\SaveRequestAnswers;
use App\Actions\Requests\SaveRequestAttachments;
use App\Models\DocumentRequest;
use Illuminate\Support\Facades\DB;

class RequestSubmissionService
{
    public function __construct(
    protected CreateRequest $createRequest,
    protected AttachServices $attachServices,
    protected SaveRequestAnswers $saveRequestAnswers,
    protected SaveRequestAttachments $saveRequestAttachments,
) {
}

    public function submit(array $data): DocumentRequest
    {
        return DB::transaction(function () use ($data) {

            /*
            |--------------------------------------------------------------------------
            | Create Request
            |--------------------------------------------------------------------------
            */

            $request = $this->createRequest->execute($data);

            /*
            |--------------------------------------------------------------------------
            | Attach Services
            |--------------------------------------------------------------------------
            */

            if (!empty($data['services'])) {

                $this->attachServices->execute(
                    $request,
                    $data['services']
                );

            }

            /*
            |--------------------------------------------------------------------------
            | Save Dynamic Answers
            |--------------------------------------------------------------------------
            */

            if (!empty($data['answers'])) {

                $this->saveRequestAnswers->execute(
                    $request,
                    $data['answers']
                );

            }

            if (!empty($data['attachments'])) {
    $this->saveRequestAttachments->execute(
        $request,
        $data['attachments']
    );
}

            return $request->load([
                'organization',
                'contact',
                'services',
                'answers.field',
            ]);

        });
    }
}