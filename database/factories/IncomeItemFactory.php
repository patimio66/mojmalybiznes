<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\IncomeItem>
 */
class IncomeItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = fake()->randomNumber(2);
        $price = fake()->randomFloat(2, 0, 10);
        $amount = round((($price ?? 0) * ((int) $quantity ?? 0)), 2);
        return [
            'title' => fake()->text(20),
            'quantity' => $quantity,
            'uom' => (bool)rand(0, 1) ? 'szt.' : 'h',
            'price'  => $price,
            'amount' => $amount,
        ];
    }
}
