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
    public function __construct()
    {
        //$this->middleware('auth:sanctum');
    }


    public function index(Request $request, Order $order)
    {
        $limit = $request->input('limit', 10);
        $items = Order::with('items.designs')->where('id', $order->id)
            ->paginate($limit)->items();


        return response()->json([
            'message' => 'success',
            'data' =>  $items
        ], 200);
    }


    public function store(StoreItemRequest $request)
    {
        $data = $request->validate([
            'design_id' => 'required|integer|exists:designs,id',
            'measure_id' => 'required|integer|exists:measures,id',
        ]);


        $customer = Customer::first();

        $design = Design::with('feature_store')->findOrFail($data['design_id']);
        $storeId = $design->feature_store->store_id;

        $storeModel = Store::with('locations')->find($storeId);
        $storeLocationId = optional($storeModel->locations->first())->id;
        $customerLocationId = optional($customer->locations->first())->id;

        if (!$storeId) {
            return response()->json(['message' => 'This design is not linked to a store'], 400);
        }

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
                'status_id'             => 1,
                'customer_location_id'  => $customerLocationId,
                'store_location_id'     => $storeLocationId,
                'total_price'           => 0,
            ]);
        }
        $item = Item::withTrashed()
            ->where('order_id', $order->id)
            ->where('measure_id', $data['measure_id'])
            ->first();

        if ($item) {
            if ($item->trashed()) {
                $item->restore();

                $item->designs()->sync([$data['design_id']]);
            } else {

                $item->designs()->syncWithoutDetaching([$data['design_id']]);
            }
        } else {
            $item = Item::create([
                'order_id'   => $order->id,
                'measure_id' => $data['measure_id'],
            ]);

            $item->designs()->attach($data['design_id']);
        }

        $total = 0;
        $order->load('items.designs');

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


    public function destroy(Item $item)
    {
        $itemDetaile = Item::with('order', 'designs')->where('id', $item->id)->first();

        if (!$itemDetaile) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        if ($itemDetaile->order->status_id !== 1) {
            return response()->json(['message' => 'Order can not be modified'], 400);
        }

        $itemDetaile->delete();

        $order = $itemDetaile->order;
        $total = 0;

        $order->load('items.designs');
        foreach ($order->items as $remainingItem) {
            foreach ($remainingItem->designs as $design) {
                $total += $design->price;
            }
        }

        $order->update(['total_price' => $total]);

        return response()->json([
            'message' => 'Item removed',
            'new total price' => $total
        ]);
    }


    public function removeDesignFromItem(Item $item, Design $design)
    {
        $itemData = Item::with(['order'])->findOrFail($item->id);

        $order = $itemData->order;

        if (!$order || $order->status_id !== 1) {
            return response()->json(['message' => 'Order not modifiable'], 400);
        }

        $item->designs()->detach($design->id);

        $order->load('items.designs');

        $total = 0;
        foreach ($order->items as $orderItem) {
            foreach ($orderItem->designs as $d) {
                $total += $d->price;
            }
        }

        $order->update(['total_price' => $total]);

        return response()->json([
            'message' => 'Design removed from item',
            'item id' => $item->id,
            'removed design id' => $design->id,
            'new total price' => $total
        ]);
    }
}
