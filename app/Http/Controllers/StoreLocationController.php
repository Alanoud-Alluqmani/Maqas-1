<?php

namespace App\Http\Controllers;

use App\Models\StoreLocation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreLocationRequest;
use App\Models\Store;

class StoreLocationController extends Controller
{

    public function __construct(){
        $this->middleware('auth:sanctum');
        // $this->middleware('super admin')->only(['index']);
        // $this->middleware('co admin')->only(['index', 'destroy']);
        // $this->middleware('store owner')->only(['destroy', 'update', 'store']);
        // $this->middleware('store employee')->only(['destroy', 'update', 'store']);
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $storeLoc = StoreLocation::all(); // Fetch all products
        return response()->json([
            'message' => 'success',
            'data' => $storeLoc // Return the products in JSON format
        ], 200);
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLocationRequest $request, Store $store)
    {
       if (!$store) {
            return response()->json(['message' => 'Store not found.'], 404);
        }

       $storeLoc = $store->locations()->create($request->validated());
        return response()->json([
            "message" => 'success', // Return success message in JSON format
            "data" => $storeLoc
        ], 200);
    }

    public function view(Store $store)
    {
        if (!$store) {
            return response()->json(['message' => 'Store not found.'], 404);
        }
        $storeLoc = $store->locations()->get();
       return response()->json([
            "message" => 'success', // Return success message in JSON format
            "data" => $storeLoc
        ], 200);
    }


    /**
     * Display the specified resource.
     */
    public function show(StoreLocation $storeLoc)
    {

         if (!$storeLoc) {
            return response()->json(['message' => 'Store Location not found.'], 404);
        }
       return response()->json([
            "message" => 'success', // Return success message in JSON format
            "data" => $storeLoc
        ], 200);
        
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreLocationRequest $request, StoreLocation $storeLoc)
    {
        if (!$storeLoc) {
            return response()->json(['message' => 'Store Location not found.'], 404);
        }
        
        $storeLoc->update($request->validated()); // Update the product with validated data
        return response()->json([
             'message' => 'store location updated successfully' ,
            'data' => $storeLoc
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StoreLocation $storeLoc)
    {
        if (!$storeLoc) {
            return response()->json(['message' => 'store location not found.'], 404);
        }

         $storeLoc->delete();

        return response()->json([ // Return a JSON response indicating success
            'message' => 'Store Location Deleted Successfully'
        ],204);
    }
}
