<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'order_reference',
        'customer_name',
        'customer_phone',
        'customer_email',
        'shipping_address',
        'total_price',
        'status',
        'employee_notes'
    ];

    // O comandă APARȚINE UNUI SINGUR magazin (belongsTo)
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    // O comandă ARE MAI MULTE produse în ea (hasMany)
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
