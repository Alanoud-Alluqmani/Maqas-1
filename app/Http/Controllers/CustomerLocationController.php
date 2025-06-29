<?php

namespace App\Http\Controllers;

use App\Models\CustomerLocation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CustomerLocationRequest;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;


class CustomerLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customerLoc = CustomerLocation::all(); 
        return response()->json([
            'message' => 'success',
            'data' => $customerLoc 
        ], 200);
    }

    



 public function store(CustomerLocationRequest $request)
    {
        //$customer=Auth::customer()->customer;
        $customer = Customer::first(); 
       if (!$customer) {
            return response()->json(['message' => 'Customer not found.'], 404);
        }

       $locUrl = "https://maps.google.com/?q={$request->latitude},{$request->longitude}";
        $customerLoc = $customer->locations()->create([
        'latitude' => $request->latitude,
        'longitude' => $request->longitude,
        'loc_url' => $locUrl,
         ]);

        return response()->json([
            "message" => 'success', // Return success message in JSON format
            "data" => $customerLoc
        ], 200);
    }


    
    public function view(Customer $customer)
    {
        if (!$customer) {
            return response()->json(['message' => 'customer not found.'], 404);
        }
        $customerLoc = $customer->locations()->get();
       return response()->json([
            "message" => 'success', // Return success message in JSON format
            "data" => $customerLoc
        ], 200);
    }


    /**
     * Display the specified resource.
     */
    public function show(CustomerLocation $customerLoc)
    {

         if (!$customerLoc) {
            return response()->json(['message' => 'customer Location not found.'], 404);
        }
       return response()->json([
            "message" => 'success', // Return success message in JSON format
            "data" => $customerLoc
        ], 200);
        
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerLocationRequest $request, CustomerLocation $customerLoc)
    {
        if (!$customerLoc) {
            return response()->json(['message' => 'customer location not found.'], 404);
        }
        
        $customerLoc->update($request->validated()); // Update the product with validated data
        return response()->json([
             'message' => 'customer location updated successfully' ,
            'data' => $customerLoc
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerLocation $customerLoc)
    {
        if (!$customerLoc) {
            return response()->json(['message' => 'customer location not found.'], 404);
        }

         $customerLoc->delete();

        return response()->json([ // Return a JSON response indicating success
            'message' => 'Customer Location Deleted Successfully'
        ],200);

    }
}
