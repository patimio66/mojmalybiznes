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
        Schema::create('contractors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('name'); // Nazwa kontrahenta
            $table->string('tax_id')->nullable(); // NIP lub inny identyfikator podatkowy
            $table->string('country')->default('pl'); // Domyślnie Polska
            $table->string('city')->nullable(); // Miasto
            $table->string('postal_code')->nullable(); // Kod pocztowy
            $table->string('address')->nullable(); // Adres kontrahenta
            $table->string('email')->nullable(); // Email kontaktowy
            $table->string('phone')->nullable(); // Telefon
            $table->text('notes')->nullable(); // Notatki
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contractors');
    }
};
