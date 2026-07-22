<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [

        'organization_id',

        'first_name',

        'middle_name',

        'last_name',

        'suffix',

        'position',

        'office',

        'email',

        'mobile',

        'telephone',

        'is_primary',

        'is_active',

        'remarks',

    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function getFullNameAttribute()
    {
        return trim(

            $this->first_name.' '.

            $this->middle_name.' '.

            $this->last_name.' '.

            $this->suffix

        );
    }
}