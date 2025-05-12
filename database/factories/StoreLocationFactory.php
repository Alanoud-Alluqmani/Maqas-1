<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Store;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StoreLocation>
 */
class StoreLocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $stores = Store::pluck('id')->toarray();

        return [
            'loc_url' => fake()->url(),
            'store_id' => fake()->randomElement($stores),
        ];
    }
}
