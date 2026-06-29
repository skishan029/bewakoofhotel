<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderIntent extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_no',
        'order_key',
        'customer_id',
        'sub_total',
        'delivery_charge',
        'discount',
        'grand_total',
        'order_date',
        'address',
        'landmark',
        'coupon_id',
        'coupon_code',
        'coupon_desc',
        'order_note',
        'order_item',
        'customer_name',
        'customer_phone',
        'email',
        'payment_date',
        'status',
        'product_order_id',
        'region_id',
        'sub_region_id',
    ];

    protected $casts = [
        'order_item' => 'json',
    ];

    protected static function booted(): void
    {
        static::creating(function (OrderIntent $orderIntent) {
            $orderIntent->order_no = time() . mt_rand(1000, 9999);
            $orderIntent->order_key = md5($orderIntent->order_no);
        });
    }

    function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id')->withDefault();
    }
}
