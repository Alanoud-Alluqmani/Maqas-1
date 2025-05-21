<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Feature;
use App\Models\Store;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FeatureStore>
 */
class FeatureStoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $features = Feature::pluck('id')->toarray();
        $stores = Store::pluck('id')->toarray();
        $stores = array_slice($stores, 1);

        return [
            'feature_id' => fake()->randomElement($features),
            'store_id' => fake()->randomElement($stores),
        ];
    }
}

  
  