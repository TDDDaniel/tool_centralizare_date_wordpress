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
        Schema::table('orders', function (Blueprint $table) {
            //Scoatem campurile vechi
            $table->dropColumn(['customer_name', 'shipping_address']);
            //Adaugam campuri
            $table->string('customer_first_name')->after('order_reference');
            $table->string('customer_last_name')->after('customer_first_name');
            // Adresa structurata (dupa email)
            $table->string('address_county')->after('customer_email');   // judet
            $table->string('address_city');                              // localitate
            $table->string('address_street');                            // strada
            $table->string('address_number');                            // numar (TEXT: poate fi "12A")
            $table->string('address_postal_code');                       // cod postal
            // Parti optionale (cine sta la casa nu are bloc/apartament)
            $table->string('address_building')->nullable();              // bloc
            $table->string('address_entrance')->nullable();              // scara
            $table->string('address_floor')->nullable();                 // etaj
            $table->string('address_apartment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Scoatem exact ce a ADAUGAT up():
            $table->dropColumn([
                'customer_first_name', 'customer_last_name',
                'address_county', 'address_city',
                'address_street', 'address_number',
                'address_postal_code', 'address_building',
                'address_entrance', 'address_floor',
                'address_apartment',
            ]);
            // Punem inapoi exact ce a SCOS up():
            $table->string('customer_name')->after('order_reference');
            $table->text('shipping_address')->nullable()->after('customer_email');
        });
    }
};
