<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\ExpenseItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition(): array
    {
        return [
            'title' => fake()->text(30),
            'amount' => 0,
            'date' => now()->subDays(rand(1, 500))->format('Y-m-d'),
            'description' => (bool)rand(0, 1) ? fake()->text(50) : '',
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Expense $expense) {
            ExpenseItem::factory()->count(rand(1, 10))->create(['expense_id' => $expense->id]);
        });
    }
}
