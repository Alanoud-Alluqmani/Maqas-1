<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Database\Factories\Order;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rating>
 */
class RatingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
          return [
                'order_id'=> Order::pluck('id')->random(),
                'rating' => $this->faker->numberBetween(0,5),
                'comment' => $this->faker->text(),
        ];
    }
}
      
