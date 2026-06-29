<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_code',
        'product_name',
        'product_name_english',
        'product_name_online',
        'sku_code',
        'full_price',
        'half_price',
        'featured_photo',
        'product_desc',
        'product_gallery',
        'seo_title',
        'seo_desc',
        'seo_desc',
        'full_lbl_show',
        'ordering',
        'half_photo',
        'shipping_charge',
        'shipping_charge',
        'is_active',
        'is_online_label_show',
        'is_split',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_online_label_show' => 'boolean',
        'is_split' => 'boolean',
    ];

    // Active products
    public function scopeOnlyActive(Builder $query)
    {
        return $query->where('is_active', true);
    }

    // Inactive products
    public function scopeOnlyInactive(Builder $query)
    {
        return $query->where('is_active', false);
    }

    public function categories()
    {
        return $this->hasMany(CategoryProduct::class, 'product_id', 'id');
    }
}
