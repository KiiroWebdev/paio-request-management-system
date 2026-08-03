<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestAnswer extends Model
{
    protected $fillable = [
        'request_id',
        'service_field_id',
        'value',
    ];

    protected $appends = [
        'formatted_value',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(
            DocumentRequest::class,
            'request_id'
        );
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(
            ServiceField::class,
            'service_field_id'
        );
    }

    /**
     * Return checkbox values as an array.
     */
    public function getFormattedValueAttribute()
    {
        $decoded = json_decode($this->value, true);

        return json_last_error() === JSON_ERROR_NONE
            ? $decoded
            : $this->value;
    }
}