<?php

namespace Database\Factories;

use App\Models\InvoiceItem;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceItemFactory extends Factory
{
    protected $model = InvoiceItem::class;

    public function definition()
    {
        return [
            'invoice_id' => Invoice::factory(),
            'title' => $this->faker->word,
            'quantity' => $this->faker->randomFloat(2, 1, 100),
            'uom' => $this->faker->randomElement(['szt', 'kg', 'm']),
            'price' => $this->faker->randomFloat(2, 10, 1000),
        ];
    }
}
