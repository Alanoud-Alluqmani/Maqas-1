<?php

namespace Database\Seeders;

use App\Models\Rating;
use Database\Factories\RatingFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RatingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       Rating::factory()->create();
    }
}
