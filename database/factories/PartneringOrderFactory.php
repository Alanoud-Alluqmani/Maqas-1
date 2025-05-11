<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Database\Factories\Store;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PartneringOrder>
 */
class PartneringOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
          return [
            'store_id'=> Store::pluck('id')->random(),
            'status' => $this->faker->randomElement(['Waiting', 'Accepted', 'Rejected']),
    ];
    }
}