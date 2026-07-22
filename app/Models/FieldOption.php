<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FieldOption extends Model
{
    protected $fillable = [
        'service_field_id',
        'label',
        'value',
        'display_order',
    ];

    /**
     * The service field this option belongs to.
     */
    public function serviceField(): BelongsTo
    {
        return $this->belongsTo(ServiceField::class);
    }
}