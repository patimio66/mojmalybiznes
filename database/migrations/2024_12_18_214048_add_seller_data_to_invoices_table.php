<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('seller_name'); // Nazwa kontrahenta
            $table->string('seller_tax_id')->nullable(); // NIP lub inny identyfikator podatkowy
            $table->string('seller_country')->default('pl'); // Domyślnie Polska
            $table->string('seller_city')->nullable(); // Miasto
            $table->string('seller_postal_code')->nullable(); // Kod pocztowy
            $table->string('seller_address')->nullable(); // Adres kontrahenta
            $table->string('seller_email')->nullable(); // Email kontaktowy
            $table->string('seller_phone')->nullable(); // Telefon
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('seller_name');
            $table->dropColumn('seller_tax_id');
            $table->dropColumn('seller_country');
            $table->dropColumn('seller_city');
            $table->dropColumn('seller_postal_code');
            $table->dropColumn('seller_address');
            $table->dropColumn('seller_email');
            $table->dropColumn('seller_phone');
        });
    }
};
