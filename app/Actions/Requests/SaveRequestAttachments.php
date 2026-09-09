<?php

namespace App\Actions\Requests;

use App\Models\Attachment;
use App\Models\DocumentRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

class SaveRequestAttachments
{
    public function execute(
        DocumentRequest $request,
        array $attachments
    ): void {

        foreach ($attachments as $attachment) {

            $file = $attachment['file'] ?? null;
            $attachmentType = $attachment['attachment_type'] ?? null;

            if (! $file instanceof UploadedFile) {
                continue;
            }

            $path = $file->store(
                'attachments/' . $request->id,
                'public'
            );

            $savedAttachment = Attachment::create([
                'request_id' => $request->id,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
                'attachment_type' => $attachmentType,
                'uploaded_by' => Auth::id(),
            ]);

            \App\Models\ActivityLog::create([
                'request_id' => $request->id,
                'user_id' => Auth::id(),
                'action' => 'Attachment Uploaded',
                'description' => "Attachment uploaded: {$savedAttachment->file_name}.",
                'old_value' => null,
                'new_value' => $savedAttachment->file_name,
                'ip_address' => request()->ip(),
            ]);
        }
    }
}