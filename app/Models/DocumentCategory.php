<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentCategory extends Model
{
    protected $fillable = [
        'name',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Documents assigned to this category.
     */
    public function archivedDocuments(): HasMany
    {
        return $this->hasMany(
            ArchivedDocument::class,
            'document_type',
            'name'
        );
    }
}