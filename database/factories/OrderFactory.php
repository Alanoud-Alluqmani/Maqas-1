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
        $customers = Customer::pluck('id')->toarray();
        $stores = Store::pluck('id')->toarray();
        $services = Service::pluck('id')->toarray();
        $statuses = Status::pluck('id')->toarray();
        $custlocs = CustomerLocation::pluck('id')->toarray();
        $storelocs = StoreLocation::pluck('id')->toarray();

        return [
            'customer_id' => fake()->randomElement($customers),
            'store_id' => fake()->randomElement($stores),
            'service_id' => fake()->randomElement($services),
            'status_id' => fake()->randomElement($statuses),
            'customer_location_id' => fake()->randomElement($custlocs),
            'store_location_id' => fake()->randomElement($storelocs),
            'total_price' => fake()->price
        ];
    }
}
