<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateOrderStatusRequest;
use App\Models\Order;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class OrderController extends Controller
{

    public function __construct(){
        $this->middleware('auth:sanctum');// currently, all methods are protected by 
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $orders = Order::all(); // Fetch all products

        if (!$orders){
            return response()->json([
            'message' => 'no orders found'
        ], 404);
        } else
        return response()->json([
            'message' => 'orders found',
            'data' => $orders // Return the products in JSON format
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
    public function show($id)
    {
        $order = Order::findOrFail($id);
        
        if (!$order){
            return response()->json([
            'message' => 'order not found'
        ], 404);
        } else
        return response()->json([
            'message' => 'order found',
            'data' => $order 
        ], 200);
    }





public function update(UpdateOrderStatusRequest $request, $order_id)
{
    $status = $request->validated();

    // Find the order
    $order = Order::findOrFail($order_id);


    // Update the order status using relationship
    $order->status()->associate($status['id']); // Associate the status
    $order->save(); // Save without mass assignment

    return response()->json([
        'message' => 'Order status updated successfully',
        'data' => [
            'status' => $status,
            'order' => $order
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
