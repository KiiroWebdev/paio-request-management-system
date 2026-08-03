<?php

namespace App\Models;

use App\Models\ServiceFieldOption;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceField extends Model
{
    protected $fillable = [

        'service_id',
        'field_type_id',
        'field_name',
        'label',
        'required',
        'placeholder',
        'help_text',
        'display_order',
        'active',

    ];

    protected $casts = [
        'required' => 'boolean',
        'active' => 'boolean',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function fieldType(): BelongsTo
    {
        return $this->belongsTo(FieldType::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(ServiceFieldOption::class)
                    ->orderBy('display_order');
    }
}