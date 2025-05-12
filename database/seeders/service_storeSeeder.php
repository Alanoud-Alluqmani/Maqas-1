<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Store;
use App\Models\Service;
use Carbon\Carbon;


class service_storeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('service_store')->insert([
        'store_id' => Store::pluck('id')->random(),
         'service_id' => Service::pluck('id')->random(),
         'created_at' => Carbon::now(),
            ]);

        // foreach ($storeIds as $storeId) {
        //     // Attach a random service ID to the store
        //     DB::table('service_store')->insert([
        //         'store_id' => $storeId,
        //         'service_id' => $serviceIds->random(),
        //         'created_at' => Carbon::now()
        //     ]);
        // }

    }
}