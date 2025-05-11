<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('stores')->insert(
            [
                'id' => '0',
                'name_ar' => 'Super Admin',
                'name_en' => 'Super Admin',
                'legal' => 'Super Admin',
                'email' => 'Super Admin',
                'phone' => '0',
                'is_active' => true,
                'product_category_id' => '0',
                'rating_avr' => '0.0',

            ],
        );
    }
}
