<?php

namespace App\Http\Controllers;

use App\Models\PartneringOrder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PartneringOrderController extends Controller
{

    public function __construct(){
        $this->middleware('auth:sanctum');// currently, all methods are protected by 
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $part_ord = PartneringOrder::all(); 
        return $part_ord;
    }

    

    /**
     * Display the specified resource.
     */
    public function show(PartneringOrder $partneringOrder)
    {
        return $partneringOrder;
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PartneringOrder $partneringOrder)
    {
        $val = $request->validate([
            'status' => ['required',
                Rule::in(['Waiting','Accepted', 'Rejected'])]
        ]);

        $partneringOrder->update($val);

        if ($partneringOrder->status == 'Rejected'){
            $store = $partneringOrder->store;
            $store->is_active = false;
            $store->save();
        }


        return response()->json([
            'partner order' => $partneringOrder, // Return the updated product
            'message' => 'partner order updated successfully' // Success message
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PartneringOrder $partneringOrder)
    {
        $partneringOrder->delete();

        return response()->json([ // Return a JSON response indicating success
            'message' => 'partner order Deleted Successfully'
        ]);
    }
}
