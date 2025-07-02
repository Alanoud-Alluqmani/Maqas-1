<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;

class RatingController extends Controller
{

    public function index()
    {
        //
    }

    public function store(Request $request, Order $order)
    {
        $data = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        if ($order->status_id != 5 && $order->status_id != 10) {
            return response()->json([
                'message' => 'You can only rate completed orders'
            ], 403);
        }

        $customer = Customer::first(); // Auth::user()->customer 
        if ($order->customer_id !== $customer->id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $alreadyRated = $order->rating()->exists();

        if ($alreadyRated) {
            return response()->json(['message' => 'You have already rated this order'], 409);
        }


        $order->rating()->create([
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);

        $store = $order->store;
        $averageRating = $store->ratings()->avg('rating');
        $store->rating_avr = round($averageRating, 2); 
        $store->save();

        return response()->json([
            'message' => 'Thank you for rating your order!',
            'data' => $data,
        ]);
    }
}
