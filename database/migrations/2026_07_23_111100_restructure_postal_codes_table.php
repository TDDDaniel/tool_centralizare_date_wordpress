<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Renuntam la cele doua tabele separate (oras / sat) in favoarea uneia singure.
        Schema::dropIfExists('city_postal_codes');
        Schema::dropIfExists('postal_codes');

        Schema::create('postal_codes', function (Blueprint $table) {
            $table->id();

            // Loc: varianta _normalized = pentru CAUTARE; cea fara sufix = pentru AFISARE.
            $table->string('county');
            $table->string('county_normalized');
            $table->string('city');
            $table->string('city_normalized');

            // Strada: tip separat de nume, ca sa nu depindem de prefix ("Str."/"Strada"/lipsa).
            $table->string('street_type')->nullable();
            $table->string('street_name');
            $table->string('street_normalized');

            // Intervalul de numere. NULL + parity 'all' = regula acopera toata strada (sate).
            $table->unsignedSmallInteger('number_from')->nullable();
            $table->unsignedSmallInteger('number_to')->nullable();
            $table->enum('parity', ['all', 'impar', 'par'])->default('all');

            // Codul: nullable, pentru strazile din orase care inca n-au cod (le pune operatorul).
            $table->char('postal_code', 6)->nullable();

            // Provenienta: oficial | operator | ... -> reimportam oficialul fara sa stergem corectiile.
            $table->string('source')->default('oficial');

            $table->timestamps();

            // Cautam de la general (oras) la specific (strada).
            $table->index(['city_normalized', 'street_normalized'], 'idx_lookup');
            $table->index(['county_normalized', 'city_normalized'], 'idx_localitate');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('postal_codes');
    }
};
