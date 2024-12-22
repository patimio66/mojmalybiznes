<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Contractor;
use App\Models\InvoiceItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        return [
            'invoice_number' => $this->faker->unique()->numerify('INV-#####'),
            'issue_date' => $this->faker->date(),
            'due_date' => $this->faker->date(),
            'seller_name' => $this->faker->company,
            'seller_address' => $this->faker->address,
            'seller_postal_code' => $this->faker->postcode,
            'seller_city' => $this->faker->city,
            'seller_country' => $this->faker->country,
            'seller_email' => $this->faker->unique()->safeEmail,
            'seller_phone' => $this->faker->phoneNumber,
            'contractor_id' => Contractor::factory(),
            'amount' => $this->faker->randomFloat(2, 100, 10000),
            'is_paid' => $this->faker->boolean,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Invoice $invoice) {
            InvoiceItem::factory()->count(rand(1, 10))->create(['invoice_id' => $invoice->id]);
        });
    }
}
