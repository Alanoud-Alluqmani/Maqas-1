<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;
use App\Models\FeatureStore;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Design>
 */
class DesignFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = FakerFactory::create('ar_JO');

        $feature_stores = FeatureStore::pluck('id')->toarray();

        return [
            'name_ar' => $faker->name,
            'name_en' => fake()->name,
            'price' => fake()->price,
            'feature_store_id' => fake()->randomElement($feature_stores)
        ];
    }
}
