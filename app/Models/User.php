<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['name', 'email', 'password', 'role', 'is_active'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
{
    return [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];
}
    public function isAdmin(): bool
{
    return $this->role === 'Admin';
}

public function isStaff(): bool
{
    return $this->role === 'Staff';
}

public function attachmentDeletionRequests()
{
    return $this->hasMany(AttachmentDeletionRequest::class, 'requested_by');
}

public function reviewedAttachmentDeletionRequests()
{
    return $this->hasMany(AttachmentDeletionRequest::class, 'reviewed_by');
}

public function requestActionRequests()
{
    return $this->hasMany(RequestActionRequest::class, 'requested_by');
}

public function reviewedRequestActionRequests()
{
    return $this->hasMany(RequestActionRequest::class, 'reviewed_by');
}
public function dashboardPreference(): HasOne
{
    return $this->hasOne(DashboardPreference::class);
}
}
