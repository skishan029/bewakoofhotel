<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_order_id',
        'product_id',
        'plate_type',
        'quantity',
        'price',
        'total',
        'status',
        'product_name',
        'product_details',
        'full_lbl_show',
    ];



    function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->withTrashed()->withDefault();
    }
}
