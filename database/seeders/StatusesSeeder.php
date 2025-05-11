<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

         DB::table('statuses')->insert(
            [
                'status_ar' => 'تم الدفع',
                'status_en' => 'Paid',
                'created_at' => Carbon::now()
            ],

            [
                'status_ar' => 'جاري التجهيز',
                'status_en' => 'Being Prepared',
                'created_at' => Carbon::now()
            ],

             [
                'status_ar' => 'جاهز للاستلام',
                'status_en' => 'Ready for Pickup',
                'created_at' => Carbon::now()
            ],

             [
                'status_ar' => 'تم الاستلام',
                'status_en' => 'Picked Up',
                'created_at' => Carbon::now()
            ],

             [
                'status_ar' => 'بانتظار المندوب',
                'status_en' => 'Waiting for The Agent',
                'created_at' => Carbon::now()
            ],

             [
                'status_ar' => 'تم ارسال المندوب',
                'status_en' => 'Agent Has Been Sent',
                'created_at' => Carbon::now()
            ],

             [
                'status_ar' => 'جاهز للتوصيل',
                'status_en' => 'Ready for Delivery',
                'created_at' => Carbon::now()
            ],

             [
                'status_ar' => 'خارج للتوصيل',
                'status_en' => 'Out for Delivery',
                'created_at' => Carbon::now()
            ],

             [
                'status_ar' => 'تم التوصيل',
                'status_en' => 'Delivered',
                'created_at' => Carbon::now()
            ],
        );
    }
}
