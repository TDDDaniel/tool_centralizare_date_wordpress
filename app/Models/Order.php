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
        'customer_first_name',
        'customer_last_name',
        'customer_phone',
        'customer_email',
        'address_county',
        'address_city',
        'address_street',
        'address_number',
        'address_postal_code',
        'address_building',
        'address_entrance',
        'address_floor',
        'address_apartment',
        'total_price',
        'status',
        'employee_notes',
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
