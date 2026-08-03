<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
        'icon',
        'color',
        'sort_order',

        'requires_request',
        'requires_organization',
        'requires_contact',
        'requires_schedule',
        'requires_attachments',
        'requires_event_details',
        'requires_approval',

        'is_active',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'is_active' => 'boolean',

        'requires_request' => 'boolean',
        'requires_organization' => 'boolean',
        'requires_contact' => 'boolean',
        'requires_schedule' => 'boolean',
        'requires_attachments' => 'boolean',
        'requires_event_details' => 'boolean',
        'requires_approval' => 'boolean',
    ];

    /**
     * Requests using this service.
     */
    public function requests(): BelongsToMany
    {
        return $this->belongsToMany(
            DocumentRequest::class,
            'request_service',
            'service_id',
            'request_id'
        );
    }

    /**
     * Dynamic fields belonging to this service.
     */
    public function fields(): HasMany
    {
        return $this->hasMany(ServiceField::class)
                    ->orderBy('display_order');
    }

   
    /**
 * Use the slug for route model binding.
 */
public function getRouteKeyName(): string
{
    return 'slug';
}
}