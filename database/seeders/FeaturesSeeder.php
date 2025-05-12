<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FeaturesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('features')->insert(
               [
                'name_ar' => 'الكم',
                'name_en' => 'Sleeve',
                'product_category_id' => 2,
                'created_at' => Carbon::now()
               ]);

               DB::table('features')->insert(
             [
                'name_ar' => 'الجيب',
                'name_en' => 'Pocket',
                'product_category_id' => 2,
                'created_at' => Carbon::now()
             ]);

             DB::table('features')->insert(
             [
                'name_ar' => 'السحاب',
                'name_en' => 'Zipper',
                'product_category_id' => 2,
                'created_at' => Carbon::now()
             ]);

             DB::table('features')->insert(
            [
                'name_ar' => 'الكم',
                'name_en' => 'Sleeve',
                'product_category_id' => 3,
                'created_at' => Carbon::now()
            ]);

            DB::table('features')->insert(
             [
                'name_ar' => 'الكبك',
                'name_en' => 'Kabak',
                'product_category_id' => 3,
                'created_at' => Carbon::now()
             ]);

             DB::table('features')->insert(
             [
                'name_ar' => 'الجيب',
                'name_en' => 'Pocket',
                'product_category_id' => 3,
                'created_at' => Carbon::now()
             ]);

             DB::table('features')->insert(
             [
                'name_ar' => 'الياقة',
                'name_en' => 'Collar',
                'product_category_id' => 3,
                'created_at' => Carbon::now()
             ]);
           
    }
}
