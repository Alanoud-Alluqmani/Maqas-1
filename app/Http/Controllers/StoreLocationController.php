<?php

namespace App\Http\Controllers;

use App\Models\StoreLocation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreLocationRequest;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;

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
        $storeLoc = StoreLocation::all(); 
        return response()->json([
            'message' => 'success',
            'data' => $storeLoc 
        ], 200);
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLocationRequest $request)
    {
        $store=Auth::user()->store;
       if (!$store) {
            return response()->json(['message' => 'Store not found.'], 404);
        }

         $locUrl = "https://maps.google.com/?q={$request->latitude},{$request->longitude}";
        $storeLoc = $store->locations()->create([
        'latitude' => $request->latitude,
        'longitude' => $request->longitude,
        'loc_url' => $locUrl,
         ]);

        return response()->json([
            "message" => 'success', // Return success message in JSON format
            "data" => $storeLoc
        ], 200);
    }

    public function view(Request $request)
    {

         $limit = $request->input('limit', 10);
         
         $store = Auth::user()->store;
        if (!$store) {
            return response()->json(['message' => 'Store not found.'], 404);
        }

    $storeLoc = StoreLocation::with(['store']) ->where('store_id', $store->id)
     ->paginate($limit)->items();

        
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
         $store = Auth::user()->store;
        if (!$store) {
            return response()->json(['message' => 'Store not found.'], 404);
        }
        
         if (!$storeLoc) {
            return response()->json(['message' => 'Store Location not found.'], 404);
        }

          if ($storeLoc->store_id !== $store->id) {
        return response()->json(['message' => 'This location does not belong to your store.'], 403);
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
        $store = Auth::user()->store;

        if (!$storeLoc) {
            return response()->json(['message' => 'Store Location not found.'], 404);
        }

          if ($storeLoc->store_id !== $store->id) {
        return response()->json(['message' => 'This location does not belong to your store.'], 403);
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

        $store = Auth::user()->store;
        
        if (!$storeLoc) {
            return response()->json(['message' => 'store location not found.'], 404);
        }
          if ($storeLoc->store_id !== $store->id) {
        return response()->json(['message' => 'This location does not belong to your store.'], 403);
     }


         $storeLoc->delete();

        return response()->json([ // Return a JSON response indicating success
            'message' => 'Store Location Deleted Successfully'
        ],200);
    }
}
