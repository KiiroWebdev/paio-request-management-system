<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArchivedDocumentActivity extends Model
{
    protected $table = 'archived_document_activity_logs';
    protected $fillable = [
        'archived_document_id',
        'user_id',
        'action',
        'description',
        'ip_address',
    ];

    // Related archived document
    public function archivedDocument(): BelongsTo
    {
        return $this->belongsTo(
            ArchivedDocument::class,
            'archived_document_id'
        );
    }

    // User who performed the activity
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}