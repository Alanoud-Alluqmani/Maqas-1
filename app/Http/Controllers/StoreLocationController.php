<?php

namespace App\Http\Controllers;

use App\Models\StoreLocation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreLocationRequest;

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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
     public function store(StoreLocationRequest $request, $id)
    {
        $request['store_id'] = $id;
        $storeLoc = StoreLocation::create($request->validated(), ['store_id' => $id]); // Create a new product with validated data
        return response()->json([
            "message" => 'success', // Return success message in JSON format
            "data" => $storeLoc
        ]);
    }




    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $storeLoc = StoreLocation::findOrFail($id);
        return $storeLoc;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StoreLocation $storeLocation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreLocationRequest $request, $id)
    {
        $storeLoc = StoreLocation::findOrFail($id); // Find the product by ID or fail if not found
        $storeLoc->update($request->validated()); // Update the product with validated data
        return response()->json([
            'store Location' => $storeLoc, // Return the updated product
            'message' => 'store Location updated successfully' // Success message
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
     $storeLoc = StoreLocation::findOrFail($id);
    $storeLoc->delete();

        return response()->json([ // Return a JSON response indicating success
            'message' => 'Store Deleted Successfully'
        ]);
    }
}
