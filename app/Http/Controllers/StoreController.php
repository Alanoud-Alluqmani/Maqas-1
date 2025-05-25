<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRequest;

class StoreController extends Controller
{

    public function __construct(){
        $this->middleware('auth:sanctum');// currently, all methods are protected by 
        // login, but will this affect the customer?
        // $this->middleware('super admin')->only(['index', 'destroy']);
        // // the index function too is supposed to be used to the customer...
        // $this->middleware('co admin')->only(['index', 'destroy']);
        // $this->middleware('store owner')->only(['destroy', 'update']);
        // $this->middleware('store employee')->only(['']);
    }

    public function addEmployee(Request $request){

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stores = Store::all(); 

        return response()->json([
            'message'=> 'success',
            'data'=>  $stores 
        ],200); 
    }

   

        
    /**
     * Display the specified resource.
     */
    public function show(Store $store)
    {

         if (!$store) {
            return response()->json(['message' => 'Store not found.'], 404);
        }
       return response()->json([
            "message" => 'success', // Return success message in JSON format
            "data" => $store
        ], 200);
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreRequest $request, Store $store)
    {
        if(!$store){
            return response()->json(['message' => 'Store not found.'], 404);
        }

      $store->update($request->validated()); 

        return response()->json([
            'message' => 'Store updated successfully',
            'store' => $store
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Store $store)
    {
            if (!$store) {
            return response()->json(['message' => 'store not found.'], 404);
        }
        $store->is_active = false;

         $store->delete();

        return response()->json([ // Return a JSON response indicating success
            'message' => 'Store Deleted Successfully'
        ],204);
    }
}
