<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PartneringOrder;

class partnering_ordersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       PartneringOrder::factory()->count(10)->create();
    }
}
