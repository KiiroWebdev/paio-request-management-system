<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Personnel extends Model
{
    protected $fillable = [
        'name',
        'position',
        'department',
        'is_active',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function requests(): BelongsToMany
    {
        return $this->belongsToMany(
            DocumentRequest::class,
            'personnel_request',
            'personnel_id',
            'request_id'
        )->withTimestamps();
    }
}