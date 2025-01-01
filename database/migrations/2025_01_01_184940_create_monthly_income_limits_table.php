<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('monthly_income_limits', function (Blueprint $table) {
            $table->id();
            $table->decimal('default', 15, 2);
            $table->decimal('unemployed', 15, 2);
            $table->date('starts_at'); // start of month
            $table->date('ends_at')->nullable(); // end of month
            $table->timestamps();
        });

        // Add default record
        DB::table('monthly_income_limits')->insert([
            'default' => 3750.00,
            'unemployed' => 2500.00,
            'starts_at' => now()->startOfMonth(),
            'ends_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_income_limits');
    }
};
