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
        Schema::create('postal_codes', function (Blueprint $table) {
            $table->id();

            // Varianta _normalized = copia curatata, dupa care CAUTAM.
            // Cea fara sufix ramane pentru AFISARE, cu diacritice cu tot.
            $table->string('county');
            $table->string('county_normalized');
            $table->string('city');
            $table->string('city_normalized');

            $table->string('street')->nullable();
            $table->string('postal_code');                  // codul (text)
            $table->string('source')->default('oficial');
            $table->timestamps();

            // Cautam mereu dupa judet + localitate: numele de sate se repeta
            // intre judete (exista un sat "Iasi" in judetul Brasov).
            $table->index(['county_normalized', 'city_normalized'], 'idx_localitate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postal_codes');
    }
};
