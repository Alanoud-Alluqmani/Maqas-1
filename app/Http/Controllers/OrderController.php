<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateOrderStatusRequest;
use App\Models\Order;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Store;


class OrderController extends Controller
{

    public function __construct(){
        $this->middleware('auth:sanctum');// currently, all methods are protected by 
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
         $orders = Order::with(['customer', 'store', 'service', 'status',
     'customer_location', 'store_location'])->paginate($limit);
        //$orders = Order::all();
       


        if (!$orders){
            return response()->json([
            'message' => 'no orders found'
        ], 404);
        } else
        return response()->json([
            'message' => 'orders found',
            'data' => $orders->items()// Return the products in JSON format
        ], 200);
    }



      public function view(Request $request)
    {
         $store=Auth::user()->store;

         $limit = $request->input('limit', 10);

        if ($store->orders()->get()->isEmpty()) {
            return response()->json(['message' => 'There is no order.'], 200);
        }
       
    $orders = $store->orders()
        ->with(['customer', 'store', 'service', 'status', 'customer_location', 'store_location'])
        ->paginate($limit);


       return response()->json([
            "message" => 'success', // Return success message in JSON format
            "data" => $orders->items()
        ], 200);
    }

   

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    // public function show($id)
    // {
    //     $order = Order::findOrFail($id);
        
    //     if (!$order){
    //         return response()->json([
    //         'message' => 'order not found'
    //     ], 404);
    //     } else
    //     return response()->json([
    //         'message' => 'order found',
    //         'data' => $order 
    //     ], 200);
    // }

    public function show($id)
{
    $order = Order::with([
        'customer',
        'store',
        'service',
        'status',
        'customer_location',
        'store_location'
    ])->findOrFail($id);

    return response()->json([
        'message' => 'order found',
        'data' => $order
    ], 200);
}





public function update(UpdateOrderStatusRequest $request, $order_id)
{
    $status = $request->validated();

       $order = Order::with([
        'status'
    ])->findOrFail($order_id);

    $order->status()->associate($status['id']); 
    $order->save(); 
    $order->load('status');


    return response()->json([
        'message' => 'Order status updated successfully',
        'data' => [
            'order' => $order,
        ],
    ]);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
