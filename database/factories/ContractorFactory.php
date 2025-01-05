<?php

namespace Database\Factories;

use App\Models\Contractor;
use App\Models\Expense;
use App\Models\Income;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractorFactory extends Factory
{
    protected $model = Contractor::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'tax_id' => $this->faker->taxpayerIdentificationNumber,
            'address' => $this->faker->streetAddress,
            'postal_code' => $this->faker->postcode,
            'city' => $this->faker->city,
            'country' => 'pl',
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Contractor $contractor) {
            Income::factory()->count(rand(1, 5))->create(['contractor_id' => $contractor->id]);
            Expense::factory()->count(rand(1, 3))->create(['contractor_id' => $contractor->id]);
        });
    }
}
