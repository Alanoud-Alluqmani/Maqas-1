<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MeasureNamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('measure_names')->insert(
            [
                'name_ar' => 'الطول',
                'name_en' => 'Height',
                'created_at' => Carbon::now()
            ]);
        DB::table('measure_names')->insert(
              [
                'name_ar' => 'قياس الخصر',
                'name_en' => 'Waist Measurement',
                'created_at' => Carbon::now()
              ]);
         DB::table('measure_names')->insert(
            [
                'name_ar' => 'طول الذراع',
                'name_en' => 'Arm Length',
                'created_at' => Carbon::now()
            ]);
            DB::table('measure_names')->insert(
            [
                'name_ar' => 'قياس الصدر',
                'name_en' => 'Chest Measurement',
                'created_at' => Carbon::now()
            ]);
            DB::table('measure_names')->insert(
            [
                'name_ar' => 'قياس الكتف',
                'name_en' => 'Sholder Measurement',
                'created_at' => Carbon::now()
            ]);
          
           
      
    }
}