<?php

namespace App\Http\Controllers;

use App\Models\PartneringOrder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PartneringOrderController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('role:Super Admin,Co-Admin');
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
        $part_ord = PartneringOrder::with([
            'store'
        ])->paginate($limit)->items();

        if (!$part_ord) {
            return response()->json([
                'message' => 'no partnering orders found'
            ], 404);
        }

        return response()->json([
            'message' => 'partnering orders found',
            'data' => $part_ord
        ], 200);
    }


    public function show(PartneringOrder $partneringOrder)
    {
        if (!$partneringOrder) {
            return response()->json([
                'message' => 'partnering order not found'
            ], 404);
        }
        $partneringOrder->load('store');

        return response()->json([
            'message' => 'partnering order found',
            'data' => $partneringOrder
        ], 200);
    }

    
    public function update(Request $request, PartneringOrder $partneringOrder)
    {
        $val = $request->validate([
            'status' => [
                'required',
                Rule::in(['Waiting', 'Accepted', 'Rejected'])
            ]
        ]);

        $partneringOrder->update($val);

        if ($partneringOrder->status == 'Rejected') {
            $store = $partneringOrder->store;
            $store->is_active = false;
            $store->save();
        } else if ($partneringOrder->status == 'Accepted') {
            $store = $partneringOrder->store;
            $store->is_active = true;
            $store->save();
        }


        return response()->json([
            'message' => 'partner order updated successfully',
            'data' => $partneringOrder,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(PartneringOrder $partneringOrder)
    // {
    //     $partneringOrder->delete();

    //     return response()->json([ // Return a JSON response indicating success
    //         'message' => 'partner order Deleted Successfully'
    //     ]);
    // }
}
