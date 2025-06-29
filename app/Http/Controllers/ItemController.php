<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Requests\StoreItemRequest;

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


    public function store(StoreItemRequest $request)
{
     $data= $request->validate([
        'order_id' => 'required|integer|exists:orders,id',
        'design_id' => 'required|integer|exists:designs,id',
        'measure_id' => 'required|integer|exists:measures,id',
        ]);

    $item = Item::create([
        'order_id' => $data['order_id'],
        'measure_id' => $data['measure_id'],
    ]);

    $item->designs()->attach($data['design_id']);

    return response()->json([
        'message' => 'Item created successfully',
        'item_id' => $item
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
