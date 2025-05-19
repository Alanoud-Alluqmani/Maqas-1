<?php

namespace App\Http\Controllers;

use App\Models\StoreLocation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreLocationRequest;
use App\Models\Store;

class StoreLocationController extends Controller
{
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
    /**
     * Display the specified resource.
     */
    public function showAll(Store $store)
    {
        $storeLoc = $store->locations()->get();
        return $storeLoc;
    }

    public function show(StoreLocation $storeLoc)
    {
       // $storeLoc = $store->locations()->get();
        return $storeLoc;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreLocationRequest $request,StoreLocation $storeLoc)
    {
        $storeLoc->update($request->validated());
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
