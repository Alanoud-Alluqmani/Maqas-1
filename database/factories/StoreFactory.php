<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
            'name_ar' => $this->faker->name,
                'name_en' => $this->faker->name,
                'legal' => $this->faker->url(),
                'email' => $this->faker->email(),
                'phone' => $this->faker->phoneNumber(),
                'is_active' => $this->faker->boolean(),
                //'product_category_id' => ProductCategory::factory(),
                'rating_avr' => $this->faker->numberBetween(0,5),

        ];
    }
}
