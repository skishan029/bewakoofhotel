<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ProductOrder extends Model
{
    use HasFactory;

    protected $casts = [
        'is_paid' => 'boolean',
    ];


    public function scopeOnlyNonCustomer(Builder $query)
    {
        return $query->whereNull('customer_id');
    }

    public function scopeOnlyCustomer(Builder $query)
    {
        return $query->whereNotNull('customer_id');
    }

    function order_items()
    {
        return $this->hasMany(ProductOrderItem::class, 'product_order_id');
    }
    function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id')->withDefault();
    }

    function region()
    {
        return $this->belongsTo(Region::class, 'region_id')->withDefault();
    }

    function sub_region()
    {
        return $this->belongsTo(Region::class, 'sub_region_id')->withDefault();
    }
}
