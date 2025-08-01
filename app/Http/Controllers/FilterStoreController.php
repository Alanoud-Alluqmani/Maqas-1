<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerLocation;
use App\Http\Controllers\CustomerLocationController;
use App\Models\Customer;
use App\Http\Resources\StorResource;

class FilterStoreController extends Controller
{


// public function nearestToCustomer()
// {
//      $customer = Customer::first(); 

//     if (!$customer) {
//         return response()->json(['message' => 'Customer not authenticated'], 401);
//     }

//     $location = $customer->locations()->latest()->first();

//     if (!$location || !$location->latitude || !$location->longitude) {
//         return response()->json(['message' => 'Customer location not found'], 404);
//     }

//     $lat = $location->latitude;
//     $lng = $location->longitude;

//     $stores = DB::table('stores')
//         ->join('store_locations', 'stores.id', '=', 'store_locations.store_id')
//         ->select('stores.*', 'store_locations.latitude', 'store_locations.longitude')
//         ->where('stores.is_active', true)
//         ->selectRaw('
//             (6371 * acos(
//                 cos(radians(?)) * cos(radians(store_locations.latitude)) *
//                 cos(radians(store_locations.longitude) - radians(?)) +
//                 sin(radians(?)) * sin(radians(store_locations.latitude))
//             )) AS distance', [$lat, $lng, $lat])
//         ->orderBy('distance')
//         ->orderByDesc('stores.rating_avr')
//         ->get();

//     return response()->json([
//         'message' => 'Stores nearest to the customer',
//         'data' => $stores
//     ], 200);
// }


public function nearestToCustomer(Request $request)
{
    $limit = $request->input('limit', 10);
    $lat = $request->input('latitude');
    $lng = $request->input('longitude');

    if (!$lat || !$lng) {
        return response()->json(['message' => 'Latitude and longitude are required'], 422);
    }

    $stores = DB::table('stores')
        ->join('store_locations', 'stores.id', '=', 'store_locations.store_id')
        ->select('stores.*', 'store_locations.latitude', 'store_locations.longitude')
        ->where('stores.is_active', true)
        ->selectRaw('
            (6371 * acos(
                cos(radians(?)) * cos(radians(store_locations.latitude)) *
                cos(radians(store_locations.longitude) - radians(?)) +
                sin(radians(?)) * sin(radians(store_locations.latitude))
            )) AS distance', [$lat, $lng, $lat])
        ->orderBy('distance')
        ->orderByDesc('stores.rating_avr')
        ->paginate($limit)->items();

    return response()->json([
        'message' => 'Stores nearest to provided location',
        'data' => $stores
    ], 200);
}


public function nearestToCustomerByCategory(Request $request, $category_id)
{
    $limit = $request->input('limit', 10);
    $lat = $request->input('latitude');
    $lng = $request->input('longitude');

    if (!$lat || !$lng) {
        return response()->json(['message' => 'Latitude and longitude are required'], 422);
    }

    $stores = DB::table('stores')
        ->join('store_locations', 'stores.id', '=', 'store_locations.store_id')
        ->where('stores.is_active', true)
        ->where('stores.product_category_id', $category_id) 
        ->select('stores.*', 'store_locations.latitude', 'store_locations.longitude')
        ->selectRaw('
            (6371 * acos(
                cos(radians(?)) * cos(radians(store_locations.latitude)) *
                cos(radians(store_locations.longitude) - radians(?)) +
                sin(radians(?)) * sin(radians(store_locations.latitude))
            )) AS distance', [$lat, $lng, $lat])
        ->orderBy('distance')
        ->orderByDesc('stores.rating_avr')
        // ->distinct()
        ->paginate($limit)->items();

    return response()->json([
        'message' => 'Stores nearest to location with specified category',
        'data' => $stores
    ], 200);
}
}