<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('services')->insert(
            [
                'name_ar' => 'توصيل',
                'name_en' => 'Delivery',
                'description_ar' => 'يتم إرسال مندوب من المتجر إلى العميل لأخذ قياساته وعند جاهزية الطلب يتم توصيله إلى موقع العميل',
                'description_en' => 'An agent from the store is sent to the customer to take their measurements, and once the order is ready, it is delivered to the customer\'s location.',
                'icon' => 'public\SVG\delivery.svg',
                'created_at' => Carbon::now()
            ]);


            DB::table('services')->insert(
            [
                'name_ar' => 'استلام',
                'name_en' => 'Pickup',
                'description_ar' => 'على العميل إدخال مقاساته على الجوال وعند جاهزية الطلب عليه التوجه إلى موقع المتجر لاستلامه.',
                'description_en' => 'The customer enters their measurements on the mobile, and once the order is ready, they must go to the store location to pick it up.',
                'icon' => 'public\SVG\pick up.svg',
                'created_at' => Carbon::now()
            ]);

    }
}