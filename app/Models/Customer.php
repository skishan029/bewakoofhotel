<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Authenticatable
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'username',
        'password',
        'otp_key',
        'otp',
        'otp_expires_at',
        'is_verified',
        'is_active',
        'profile_picture',
        'phone_number',
        'email',
        'whatsapp_number',
        'address',
        'landmark',
        'city',
        'state',
        'country',
        'zip_code',
        'region_id',
        'subregion_id',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
    ];

    function region()
    {
        return $this->belongsTo(Region::class, 'region_id')->withDefault();
    }

    function sub_region()
    {
        return $this->belongsTo(Region::class, 'sub_region_id')->withDefault();
    }
}
