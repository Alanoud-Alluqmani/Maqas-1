<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Measure;

class MeasureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Measure::factory()->count(10)->create();
    }
}
