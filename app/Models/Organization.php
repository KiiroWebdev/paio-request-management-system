<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = [

        'name',

        'short_name',

        'type',

        'is_internal',

        'is_active',

        'address',

        'telephone',

        'email',

        'website',

    ];

    protected $casts = [

        'is_internal' => 'boolean',

        'is_active' => 'boolean',

    ];

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
}