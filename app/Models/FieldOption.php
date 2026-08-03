<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FieldOption extends Model
{
    protected $fillable = [
        'service_field_id',
        'label',
        'value',
        'display_order',
        'active',
    ];

    public function field()
    {
        return $this->belongsTo(ServiceField::class, 'service_field_id');
    }
}