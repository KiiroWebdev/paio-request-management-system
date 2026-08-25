<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DocumentRequest extends Model
{
    protected $table = 'requests';

    protected $fillable = [

        'control_no',

        'date_received',

        'received_by',

        'request_type',

        'has_request_letter',

        'title',

        'organization_id',

        'contact_id',

        'subject',

        'description',

        'status',

        'priority',

        'due_date',

        'completed_at',

        'archived_at',

        'created_by',

        'assigned_to',

        'assigned_at',

    ];

    protected $casts = [

        'date_received' => 'date',

        'has_request_letter' => 'boolean',

        'due_date' => 'date',

        'completed_at' => 'datetime',

        'archived_at' => 'datetime',

        'assigned_at' => 'datetime',

    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function services(): BelongsToMany
{
    return $this->belongsToMany(
        Service::class,
        'request_service',
        'request_id',
        'service_id'
    );
}

    public function answers(): HasMany
    {
        return $this->hasMany(RequestAnswer::class, 'request_id');
    }



    public function activityLogs(): HasMany
{
    return $this->hasMany(
        ActivityLog::class,
        'request_id'
    )->latest();
}
 public function attachments(): HasMany
{
    return $this->hasMany(
        Attachment::class,
        'request_id'
    )->latest();
}
}
