<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;


class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert(
            [
                'role' => 'Super Admin',
                'created_at' => Carbon::now()
            ]);


            DB::table('roles')->insert(
            [  'role' => 'Co-Admin',
                'created_at' => Carbon::now()
            ]);

            DB::table('roles')->insert(
            [    'role' => ' Store Owner',
                'created_at' => Carbon::now()
            ]);

            DB::table('roles')->insert(
            [ 'role' => 'Store Employee',
                'created_at' => Carbon::now()
            ]);
        
    }
}
