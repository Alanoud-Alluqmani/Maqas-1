<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerLocation>
 */
class CustomerLocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $customers = Customer::pluck('id')->toarray();

        return [
            'loc_url' => fake()->url(),
            'customer_id' => fake()->randomElement($customers),
        ];
    }
}
