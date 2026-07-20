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
        'street',
        'postal_code',
        'source',
        'normalized',
    ];
}
