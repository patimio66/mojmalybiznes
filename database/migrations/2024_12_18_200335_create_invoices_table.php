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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('income_id')->constrained();
            $table->string('invoice_number');
            $table->date('issue_date');
            $table->date('transaction_date');
            $table->date('due_date');
            $table->string('description')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('tax_exemption_type'); // 'objective' - przedmiotowe, 'subjective' - podmiotowe
            $table->boolean('is_paid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
