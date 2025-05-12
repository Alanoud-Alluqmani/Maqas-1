<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;
use App\Models\Store;
use App\Models\Service;
use App\Models\Status;
use App\Models\CustomerLocation;
use App\Models\StoreLocation;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::pluck('id')->random(),
            'store_id' => Store::pluck('id')->slice(1)->random(),
            'service_id' => Service::pluck('id')->random(),
            'status_id' => Status::pluck('id')->random(),
            'customer_location_id' => CustomerLocation::pluck('id')->random(),
            'store_location_id' => StoreLocation::pluck('id')->random(),
            'total_price' => fake()->randomFloat(2,0,400),
        ];
    }
}
