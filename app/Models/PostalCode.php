<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostalCode extends Model
{
    protected $fillable = [
        'county',
        'county_normalized',
        'city',
        'city_normalized',
        'street_type',
        'street_name',
        'street_normalized',
        'number_from',
        'number_to',
        'parity',
        'postal_code',
        'source',
    ];

    protected $casts = [
        'number_from' => 'integer',
        'number_to' => 'integer',
    ];
}
