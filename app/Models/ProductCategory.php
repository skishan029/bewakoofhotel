<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    use HasFactory, SoftDeletes;

    public function parent_category()
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id', 'id')->withTrashed()->withDefault();
    }
}
