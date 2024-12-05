<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Income>
 */
class IncomeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->text(30),
            'amount' => fake()->randomFloat(2),
            'date' => now()->subDays(rand(1, 500))->format('Y-m-d'),
            'description' => (bool)rand(0, 1) ? fake()->text(50) : '',
        ];
    }
}
