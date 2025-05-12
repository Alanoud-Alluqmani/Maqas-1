<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;
use App\Models\Customer;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Measure>
 */
class MeasureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = FakerFactory::create('ar_JO');

        $customers = Customer::pluck('id')->toarray();

        return [
            'name' => $faker->name,
            'customer_id' => fake()->randomElement($customers),
        ];
    }
}
