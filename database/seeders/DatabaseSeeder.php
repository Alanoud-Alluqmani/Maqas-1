<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\factory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
            ServicesSeeder::class,
            Product_categoriesSeeder::class,
            MeasureNamesSeeder::class,
            StoresSeeder::class,
            UsersSeeder::class,
            CustomerSeeder::class,
            CustomerLocationSeeder::class,
            FeaturesSeeder::class,
            FeatureStoresSeeder::class,
            StoreLocationSeeder::class,
            DesignSeeder::class,
            ImageSeeder::class,
            StatusesSeeder::class,
            OrderSeeder::class,
            MeasureSeeder::class,
            ItemSeeder::class,
            RatingsSeeder::class,
            partnering_ordersSeeder::class,
            measure_valuesSeeder::class,

        ]);
    }
}

