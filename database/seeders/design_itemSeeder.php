<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Design;
use App\Models\Item;
use Carbon\Carbon;

class design_itemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('design_item')->insert([
        'design_id' => Design::pluck('id')->random(),
         'item_id' => Item::pluck('id')->random(),
         'created_at' => Carbon::now(),
            ]);
    }
}
