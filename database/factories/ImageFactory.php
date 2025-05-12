<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Design;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{

    public function definition(): array
    {
        $designs = Design::pluck('id');

        return [
            'image' => fake()->imageUrl,
            'design_id' => fake()->randomElement($designs)
        ];
    }
}
