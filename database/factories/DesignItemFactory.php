<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Item;
use App\Models\Design;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DesignItem>
 */
class DesignItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $items = Item::pluck('id')->toarray();

        return [ 
            'design_id' => Design::pluck('id')->toarray(),
            'item_id' => fake()->randomElement($items),
        ];
    }
}
