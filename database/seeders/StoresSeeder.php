<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\ProductCategory;
use App\Models\Store;
use Carbon\Carbon;

class StoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $store = Store::factory(1)->make([
        //         'name_ar' => 'Super Admin',
        //         'name_en' => 'Super Admin',
        //         'legal' => 'Super Admin',
        //         'email' => 'Super Admin',
        //         'phone' => '0',
        //         'is_active' => true,
        //         //'product_category_id' => ProductCategory::factory(),
        //         'rating_avr' => '0.0',
        // ])
        // ->for(ProductCategory::factory()->state([
        //     'name_ar' => 'القائد',
        //     'name_en' => 'Super Admin',
        // ])); 
        DB::table('stores')->insert(
            [
                

               'name_ar' => 'Super Admin',
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

        Store::factory()->count(50)->create();
    }
}
