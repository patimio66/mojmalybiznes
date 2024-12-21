<?php

namespace Database\Factories;

use App\Models\IncomeItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\IncomeItem>
 */
class IncomeItemFactory extends Factory
{
    protected $model = IncomeItem::class;

    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 5);
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
