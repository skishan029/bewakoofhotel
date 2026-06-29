<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Region extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'parent_id',
        'price',
        'state_id',
        'district_id',
        'city_id',
    ];

    public function subregion()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }
    public function region()
    {
        return $this->belongsTo(self::class, 'parent_id', 'id')->withDefault();
    }
}
