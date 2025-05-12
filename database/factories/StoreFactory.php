<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = FakerFactory::create('ar_JO');

        $prod_categs = ProductCategory::pluck('id');

        return [
                'name_ar' => $faker->name,
                'name_en' => $this->faker->name,
                'legal' => $this->faker->filePath,
                'email' => $this->faker->email(),
                'phone' => $this->faker->phoneNumber(),
                'is_active' => $this->faker->boolean(),
                'product_category_id' => fake()->randomElement($prod_categs),
                'rating_avr' => $this->faker->numberBetween(0,5),

        ];
    }
}
