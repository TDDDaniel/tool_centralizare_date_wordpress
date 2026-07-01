<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    // 1. $fillable ne protejează baza de date. Îi spune lui Laravel
    // exact ce coloane au voie să fie completate automat (ex: la importul Excel)
    protected $fillable = [
        'name',
        'type',
        'domain_or_address',
        'tva_rate',
        'price_includes_tva'
    ];

    // 2. Definim relația: Un magazin ARE MAI MULTE comenzi (hasMany)
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
