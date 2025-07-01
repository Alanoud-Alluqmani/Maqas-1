<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Requests\StoreItemRequest;
use App\Models\Design;
use App\Models\Store;
use App\Models\Customer;
use App\Models\FeatureStore;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Order $order)
    {
         $limit = $request->input('limit', 10);
        $items = Order::with('items')->where('id', $order->id)
        ->paginate($limit)->items(); 

        
        return response()->json([
            'message'=> 'success',
            'data'=>  $items 
        ],200); 
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */


//     public function store(StoreItemRequest $request)
// {
//      $data = $request->validate([
//         // 'order_id' => 'required|integer|exists:orders,id',
//         'design_id' => 'required|integer|exists:designs,id',
//         'measure_id' => 'required|integer|exists:measures,id',
//         ]);


//     $item = Item::create([
//         'order_id' => $data['order_id'],
//         'measure_id' => $data['measure_id'],
//     ]);

//     $item->designs()->attach($data['design_id']);

//     return response()->json([
//         'message' => 'Item created successfully',
//         'item_id' => $item
//     ]);
// }



     public function store(StoreItemRequest $request)
{
     $data = $request->validate([
        // 'order_id' => 'required|integer|exists:orders,id',
        'design_id' => 'required|integer|exists:designs,id',
        'measure_id' => 'required|integer|exists:measures,id',
        ]);

        // $customer = Auth::user();
        //  if (!$customer) {
        // return response()->json(['message' => 'Unauthorized'], 401);
        // }

        $customer = Customer::first(); 

       $design = Design::with('feature_store')->findOrFail($data['design_id']);
       $storeId = $design->feature_store->store_id;

       $storeModel = Store::with('locations')->find($storeId);
       $storeLocationId = optional($storeModel->locations->first())->id;
       $customerLocationId = optional($customer->locations->first())->id;




         if (!$storeId) {
        return response()->json(['message' => 'This design is not linked to a store'], 400);
         }


       
        // $order = Order::firstOrCreate([
        //     'customer_id' => $customer->id,
        //     'store_id' => $storeId, 
        //     'service_id' => 1, 
        //     'status_id'=> 1,
        //     'customer_location_id' => $customerLocationId,
        //     'store_location_id'=> $storeLocationId,
        //     'total_price' => 0, 

        // ]);

        

         $existingOrder = Order::where([
        'customer_id' => $customer->id,
        'store_id'    => $storeId,
        'status_id'   => 1,
    ])->first();

    if ($existingOrder) {
        $order = $existingOrder;
    } else {
        $order = Order::create([
            'customer_id'           => $customer->id,
            'store_id'              => $storeId,
            'service_id'            => 1,
            'status_id'             => 1, // new pending order
            'customer_location_id'  => $customerLocationId,
            'store_location_id'     => $storeLocationId,
           // 'total_price'           => 0,
        ]);
    }



    $item = Item::create([
        'order_id' => $order->id,
        'measure_id' => $data['measure_id'],
    ]);

    $item->designs()->attach($data['design_id']);

    $order->load('items.designs');

// Recalculate total from all designs on the order
$total = 0;
foreach ($order->items as $item) {
    foreach ($item->designs as $design) {
        $total += $design->price;
    }
}

$order->total_price = $total;
$order->save();


   

    return response()->json([
        'message' => 'Item created successfully',
        'item_id' => $item
    ]);
}

// public function store(StoreItemRequest $request)
// {
//     $data = $request->validate([
//         'design_id'  => 'required|integer|exists:designs,id',
//         'measure_id' => 'required|integer|exists:measures,id',
//     ]);

//     // Step 1: Find or create a "pending" order for the logged-in customer
//     // $customer = Auth::user(); 
//     // $customerId = $customer['id'];

//     $customer = Auth::user();  
//     $storeId = $customer->store_id; 

// if (!$customer) {
//     return response()->json(['message' => 'Unauthorized'], 401);
// }

// $customerId = $customer->id;

//     // $order = Order::firstOrCreate(
//     //     ['customer_id' => $customerId, 'status_id' => 1], // pending
//     //     ['total_price' => 0] // or any default values
//     // );


//     $order = Order::firstOrCreate(
//     ['customer_id' => $customerId, 'status_id' => 1], // search condition
//     [
//         'total_price' => 0,
//         'store_id' => $storeId, 
//         'service_id' => 1 , 
//          'store_id', 
//          'service_id', 
//          'status_id',
//      'customer_location_id',
//       'store_location_id'
//     ]
// );

//     // Step 2: Create the new item under that order
//     $item = Item::create([
//         'order_id'   => $order->id,
//         'measure_id' => $data['measure_id'],
//     ]);

//     // Step 3: Attach the design to the item
//     $item->designs()->attach($data['design_id']);

//     return response()->json([
//         'message' => 'Item stored and linked to order successfully',
//         'order_id' => $order->id,
//         'item_id' => $item->id,
//     ]);
// }


// public function store(StoreItemRequest $request)
// {
//     $data = $request->validate([
//         'design_id'  => 'required|integer|exists:designs,id',
//         'measure_id' => 'required|integer|exists:measures,id',
//     ]);

    
//     $customer = Auth::user();
//     if (!$customer) {
//         return response()->json(['message' => 'Unauthorized'], 401);
//     }

    
//     $design = \App\Models\Design::with('feature_store')->findOrFail($data['design_id']);
//     $storeId = $design->store_id;

//     // (Optional) Set default location values if needed
//     $customerLocationId = $customer->default_location_id ?? null;
//     $storeLocationId = $design->store->default_location_id ?? null;

//     // Step 2: Find or create a pending order for this customer and store
//     $order = Order::firstOrCreate(
//         ['customer_id' => $customer->id, 
//         'status_id' => 0,
        
//             'store_id'              => 12,
//             'service_id'            => 1,
//             // 'status_id'             => 0,
//             'total_price'           => 0,
//             'customer_location_id'  => 2,
//             'store_location_id'     => 2,
//         ]
//     );

//     // Step 3: Create the item and attach the design
//     $item = Item::create([
//         'order_id'   => $order->id,
//         'measure_id' => $data['measure_id'],
//     ]);

//     $item->designs()->attach($data['design_id']);

//     return response()->json([
//         'message' => 'Item created and attached to order successfully.',
//         'order_id' => $order->id,
//         'item_id'  => $item->id,
//     ]);
// }

public function chooseService(Request $request)
{
    $validated = $request->validate([
        'order_id' => 'required|exists:orders,id',
        'service_id' => 'required|exists:services,id'
    ]);

   
    $order = Order::with('store.services')->find($validated['order_id']);

    if (!$order || !$order->store) {
        return response()->json(['message' => 'Store not found for this order.'], 404);
    }

    $store = $order->store;


    $serviceExists = $store->services->contains('id', $validated['service_id']);

    if (!$serviceExists) {
        return response()->json(['message' => 'Selected service is not offered by this store.'], 400);
    }

    
    $order->update([
        'service_id' => $validated['service_id']
    ]);

    return response()->json([
        'message' => 'Service successfully selected for the order.',
        'data' => $order
    ]);
}

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        //
    }
}
