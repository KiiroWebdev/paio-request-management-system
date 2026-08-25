<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'request_id',
        'user_id',
        'action',
        'description',
        'old_value',
        'new_value',
        'ip_address',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(
            DocumentRequest::class,
            'request_id'
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }
}