<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Design;
use Carbon\Carbon;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Item::factory()->count(10)->create();
        
foreach (Item::all() as $item) {
    $designs = Design::inRandomOrder()->take(rand(1, 5))->pluck('id');
    $attachData = [];
    
    foreach ($designs as $designId) {
        $attachData[$designId] = ['created_at' => Carbon::now()];
    }
    
    $item->designs()->attach($attachData);
}
    
    }
}
