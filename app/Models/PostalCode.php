<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostalCode extends Model
{
    protected $fillable = [
        'county',
        'city',
        'street',
        'postal_code',
        'source',
    ];
}
