<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('city_postal_codes', function (Blueprint $table) {
            $table->id();

            // ORASUL. Varianta _normalized e cea dupa care cautam efectiv.
            $table->string('county');            // Cluj
            $table->string('city');              // Cluj-Napoca
            $table->string('city_normalized');   // cluj-napoca

            // STRADA. Tipul stat separat de nume, ca sa nu depindem de prefix
            // ("Str." / "Strada" / lipsa) cand comparam.
            $table->string('street_type')->nullable();  // Strada / Bulevardul / Calea
            $table->string('street_name');              // Ştefan cel Mare
            $table->string('street_normalized');        // stefan cel mare

            // INTERVALUL DE NUMERE. Doua coloane INT, ca sa putem compara in SQL:
            //   WHERE number_from <= 15 AND number_to >= 15
            // Ambele null + parity 'all' = regula acopera toata strada.
            $table->unsignedSmallInteger('number_from')->nullable();
            $table->unsignedSmallInteger('number_to')->nullable();
            $table->enum('parity', ['all', 'impar', 'par'])->default('all');

            // RASPUNSUL
            $table->char('postal_code', 6);

            // DE UNDE STIM: oficial | osm | api | operator
            // Ne lasa sa reimportam datele oficiale fara sa stergem ce au
            // invatat operatorii la telefon.
            $table->string('source')->default('oficial');

            $table->timestamps();

            // Indexul principal: exact coloanele dupa care filtram la pasul 1,
            // de la general (oras) la specific (strada).
            $table->index(['city_normalized', 'street_normalized'], 'idx_lookup');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('city_postal_codes');
    }
};
