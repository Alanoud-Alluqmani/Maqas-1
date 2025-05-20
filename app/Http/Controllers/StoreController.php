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
        return $stores; 
    }

   

        
    /**
     * Display the specified resource.
     */
    public function show(Store $store)
    {
        return $store;
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreRequest $request, Store $store)
    {
         // Validate and retrieve the data
      $store->update($request->validated()); 
        return response()->json([
            'store' => $store, 
            'message' => 'Store updated successfully' // Success message
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Store $store)
    {
     $store->is_active = false;
     $store->delete();

        return response()->json([ // Return a JSON response indicating success
            'message' => 'Store Deleted Successfully'
        ]);
    }
}
