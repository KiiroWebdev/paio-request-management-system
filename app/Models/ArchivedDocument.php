<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ArchivedDocument extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'document_type',
        'document_number',
        'subject',
        'document_date',
        'originating_office',
        'recipient',
        'remarks',
        'file_path',
        'original_file_name',
        'file_size',
        'uploaded_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'document_date' => 'date',
            'file_size' => 'integer',
        ];
    }

    // User who uploaded the document
    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // User who last updated the document
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Document activity history
    public function activities(): HasMany
    {
        return $this->hasMany(
            ArchivedDocumentActivity::class,
            'archived_document_id'
        );
    }
}