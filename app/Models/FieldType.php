<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FieldType extends Model
{
    protected $fillable = [
        'name',
        'key',
        'icon',
        'description',
    ];

    public function serviceFields(): HasMany
    {
        return $this->hasMany(ServiceField::class);
    }
}