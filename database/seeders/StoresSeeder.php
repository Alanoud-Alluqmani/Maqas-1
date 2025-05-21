<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\ProductCategory;
use App\Models\Store;
use App\Models\Service;
use App\Models\Feature;
use Carbon\Carbon;

class StoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('stores')->insert(
            [
               'name_ar' => 'القائد',
                'name_en' => 'Super Admin',
                'legal' => 'Super Admin',
                'email' => 'Super Admin',
                'phone' => '0',
                'is_active' => true,
                'product_category_id' => 1,
                'rating_avr' => '0.0',
                'created_at' => Carbon::now()
            ],
        );

        $store_first = Store::all()->first();
        $store_first->partnering_order()->create();

        Store::factory()->count(10)->create();

        foreach(Store::all() as $store){
            $services = Service::inRandomOrder()->take(rand(1, 2))->pluck('id');
            // $features = Feature::inRandomOrder()->take(rand(1, 2))->pluck('id');
            // echo $services;
            // echo $features;
            $store->services()->attach($services);
            // $store->features()->attach($features);
        }
    }
}
