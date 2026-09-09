<?php

namespace App\Models;

use App\Models\DocumentRequest;
use App\Models\AttachmentDeletionRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attachment extends Model
{
    protected $fillable = [
        'request_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'attachment_type',
        'uploaded_by',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(
            DocumentRequest::class,
            'request_id'
        );
    }

    public function deletionRequests()
{
    return $this->hasMany(AttachmentDeletionRequest::class);
}

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'uploaded_by'
        );
    }

    
}