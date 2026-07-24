<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('postal_codes', function (Blueprint $table) {
            // ANAF are coduri cu sufix de oficiu postal (ex. 210001OPRM) - char(6) e prea mic
            $table->string('postal_code', 20)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('postal_codes', function (Blueprint $table) {
            $table->char('postal_code', 6)->nullable()->change();
        });
    }
};
