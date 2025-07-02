<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateOrderStatusRequest;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\Store;



class OrderController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('role:Super Admin,Co-Admin')->only(['index']);
        $this->middleware('role:Store Owner,Store Employee')->only(['view', 'update']);
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);

        $validOrders = Order::where('status_id', '>', 1)
            ->with(['customer', 'store', 'service', 'status', 'customer_location', 'store_location']);

        if (!$validOrders->exists()) {
            return response()->json([
                'message' => 'no orders found'
            ], 404);
        }

        $orders = $validOrders->paginate($limit);

        if (!$orders) {
            return response()->json([
                'message' => 'no orders found'
            ], 404);
        } else
            return response()->json([
                'message' => 'orders found',
                'data' => $orders->items()
            ], 200);
    }



    public function view(Request $request)
    {
        $store = Auth::user()->store;

        $limit = $request->input('limit', 10);

        $validOrders = $store->orders()->where('status_id', '>', 1);

        if (!$validOrders->exists()) {
            return response()->json(['message' => 'There is no order.'], 200);
        }

        $orders = $validOrders
            ->with(['customer', 'store', 'service', 'status', 'customer_location', 'store_location'])
            ->paginate($limit);

        return response()->json([
            "message" => 'success',
            "data" => $orders->items()
        ], 200);
    }

    public function show($id)
    {
        $order = Order::with([
            'customer',
            'store',
            'service',
            'status',
            'customer_location',
            'store_location',
            'items.designs',
            'items.measure.secondary_measures.name',


        ])->findOrFail($id);

        $authStoreId = Auth::user()->store_id;

        if ($order->store_id !== $authStoreId) {
            return response()->json([
                'message' => 'You are not authorized to view this order.',
            ], 403);
        }

        $orderData = $order->toArray();

        if ($order->status_id == 10 || $order->status_id == 5) {
            $order->load('rating');
            $orderData['rating'] = $order->rating;
        }


        if ($order->status_id == 1) {
            return response()->json([
                'message' => 'It is a pending order.',
            ], 403);
        }



        return response()->json([
            'message' => 'order found',
            'data' => $orderData,

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


    public function invoice(Order $order)
    {
        $orderDetails = Order::with(
            'customer',
            'store',
            'service',
            'status',
            'customer_location',
            'store_location',
            'items.designs',
            'items.measure.secondary_measures.name',


        )->where('id', $order->id)->firstOrFail();

        $orderDetails->status_id = 2;
        $orderDetails->save();

        return response()->json([
            'message' => 'Invoice',
            'data' => $orderDetails
        ]);
    }

    // public function customerOrderDetails($id)
    // {
    //     $customer = Customer::first(); 

    //     $order = Order::with([
    //         'customer',
    //         'store',
    //         'service',
    //         'status',
    //         'customer_location',
    //         'store_location',
    //         'items.designs',
    //         'items.measure.secondary_measures.name',
    //     ])->find($id);


    //     if (!$order) {
    //         return response()->json([
    //             'message' => 'Order not found.'
    //         ], 404);
    //     }

    //         if ($order->customer_id !== $customer->id) {
    //         return response()->json([
    //             'message' => 'You are not authorized to view this order.',
    //         ], 403);
    //     }

    //     if ($order->status_id != 2) {
    //         return response()->json([
    //             'message' => 'You can only view the order details after payment is completed.',
    //         ], 403);
    //     }

    //     return response()->json([
    //         'message' => 'Order details retrieved successfully.',
    //         'data' => $order,
    //     ], 200);
    // }


    public function customerOrderDetails($orderId)
    {
        //$customer = Customer::find($customerId);
        $order = Order::with([
            'customer',
            'store',
            'service',
            'status',
            'customer_location',
            'store_location',
            'items.designs',
            'items.measure.secondary_measures.name',
        ])->find($orderId);

        if (!$order) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        // if (!$customer || $order->customer_id !== $customer->id) {
        //     return response()->json(['message' => 'Unauthorized'], 403);
        // }

        if ($order->status_id != 2) {
            return response()->json(['message' => 'Payment not completed.'], 403);
        }

        return response()->json(['message' => 'Success', 'data' => $order], 200);
    }
}
