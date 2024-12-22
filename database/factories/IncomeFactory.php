<?php

namespace Database\Factories;

use App\Models\Income;
use App\Models\IncomeItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Income>
 */
class IncomeFactory extends Factory
{
    protected $model = Income::class;

    public function definition(): array
    {
        return [
            'title' => fake()->text(30),
            'amount' => 0,
            'date' => now()->subDays(rand(1, 500))->format('Y-m-d'),
            'notes' => (bool)rand(0, 1) ? fake()->text(50) : null,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Income $income) {
            IncomeItem::factory()->count(rand(1, 10))->create(['income_id' => $income->id]);
        });
    }
}
