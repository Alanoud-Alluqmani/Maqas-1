<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert(
            [
                'name_ar' =>'القائد',
                'name_en' => 'Super Admin',
                'role_id' => 1,
                'phone' => '055',
                'email' => 'superadmin@gmail.com',
                'store_id' => 1,
                'password' => Hash::make('password'),
                'created_at' => Carbon::now()
            ],
        );

        User::factory()->count(10)->create();
    }
}
