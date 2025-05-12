<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Store;



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
            'store_id' => Store::pluck('id')->slice(1)->random(),
            'status' => $this->faker->randomElement(['Waiting', 'Accepted', 'Rejected']),
    ];
    }
}
