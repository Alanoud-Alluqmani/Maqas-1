<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\Measure;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $orders = Order::pluck('id')->toarray();
        $measures = Measure::pluck('id')->toarray();

        return [
            'order_id' => fake()->randomElement($orders),
            'measure_id' => fake()->randomElement($measures),
        ];
    }
}
