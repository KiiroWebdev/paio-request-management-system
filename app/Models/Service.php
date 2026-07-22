<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
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

    public function requests()
    {
        return $this->belongsToMany(
            DocumentRequest::class,
            'request_service',
            'service_id',
            'request_id'
        );
    }
}