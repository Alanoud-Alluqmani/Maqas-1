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
            'data' => $storeLoc // Return the products in JSON format
        ]);
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLocationRequest $request, Store $store)
    {
       
       $storeLoc = $store->locations()->create($request->validated());
        return response()->json([
            "message" => 'success', // Return success message in JSON format
            "data" => $storeLoc
        ]);
    }

    public function showAll(Store $store)
    {
        $storeLoc = $store->locations()->get();
        return $storeLoc;
    }


    /**
     * Display the specified resource.
     */
    public function show(StoreLocation $storeLoc)
    {
        
        return $storeLoc;
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreLocationRequest $request, StoreLocation $storeLoc)
    {
        $storeLoc->update($request->validated()); // Update the product with validated data
        return response()->json([
            'store Location' => $storeLoc, 
            'message' => 'store Location updated successfully' // Success message
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StoreLocation $storeLoc)
    {
    
    $storeLoc->delete();

        return response()->json([ // Return a JSON response indicating success
            'message' => 'Store Location Deleted Successfully'
        ]);
    }
}
