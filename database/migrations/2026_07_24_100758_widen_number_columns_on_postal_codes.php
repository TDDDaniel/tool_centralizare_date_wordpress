<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('postal_codes', function (Blueprint $table) {
            // ANAF are intervale deschise (ex. 30-999999) care depasesc SMALLINT (max 65535)
            $table->unsignedInteger('number_from')->nullable()->change();
            $table->unsignedInteger('number_to')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('postal_codes', function (Blueprint $table) {
            $table->unsignedSmallInteger('number_from')->nullable()->change();
            $table->unsignedSmallInteger('number_to')->nullable()->change();
        });
    }
};
