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
            $table->foreignId('user_id')->constrained();
            $table->foreignId('contractor_id')->constrained();
            $table->string('contractor_name'); // Nazwa kontrahenta
            $table->string('contractor_tax_id')->nullable(); // NIP lub inny identyfikator podatkowy
            $table->string('contractor_country')->default('pl'); // Domyślnie Polska
            $table->string('contractor_city')->nullable(); // Miasto
            $table->string('contractor_postal_code')->nullable(); // Kod pocztowy
            $table->string('contractor_address')->nullable(); // Adres kontrahenta
            $table->string('contractor_email')->nullable(); // Email kontaktowy
            $table->string('contractor_phone')->nullable(); // Telefon
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['user_id', 'contractor_id']);
            $table->dropColumn('user_id');
            $table->dropColumn('contractor_id');
            $table->dropColumn('contractor_name');
            $table->dropColumn('contractor_tax_id');
            $table->dropColumn('contractor_country');
            $table->dropColumn('contractor_city');
            $table->dropColumn('contractor_postal_code');
            $table->dropColumn('contractor_address');
            $table->dropColumn('contractor_email');
            $table->dropColumn('contractor_phone');
        });
    }
};
