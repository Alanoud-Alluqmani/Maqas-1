<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\ProductCategory;
use Carbon\Carbon;

class Product_categoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('product_categories')->insert(
          [
                'name_ar' => 'القائد',
                'name_en' => 'Super Admin',
                'created_at' => Carbon::now()
          ]);
          
          
          DB::table('product_categories')->insert(
            [
                'name_ar' => 'عباية',
                'name_en' => 'Abayah',
                'created_at' => Carbon::now()
            ]);


            DB::table('product_categories')->insert(
             [
                'name_ar' => 'ثوب',
                'name_en' => 'Thobe',
                'created_at' => Carbon::now()
             ]);
        
    }
}
