<?php

namespace App\Http\Controllers;

use App\Models\CustomerLocation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CustomerLocationRequest;
use App\Models\Customer;
use App\Http\Resources\CustomerLocationResource;
use Illuminate\Support\Facades\Auth;


class CustomerLocationController extends Controller
{

    public function index()
    {
        $customerLoc = CustomerLocation::all();
        return response()->json([
            'message' => 'success',
            'data' => CustomerLocationResource::collection($customerLoc)
        ], 200);
    }



    public function store(CustomerLocationRequest $request)
    {
        $customer = Auth::user();
        $customer = Customer::find($customer->id);

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
            "message" => 'success',
            "data" => new CustomerLocationResource($customerLoc)
        ], 200);
    }



    public function view(Customer $customer)
    {
        if (!$customer) {
            return response()->json(['message' => 'customer not found.'], 404);
        }

        $customerLoc = $customer->locations()->get();



        return response()->json([
            "message" => 'success',
            "data" =>  CustomerLocationResource::collection($customerLoc)
        ], 200);
    }


    public function show(CustomerLocation $customerLoc)
    {

        if (!$customerLoc) {
            return response()->json(['message' => 'customer Location not found.'], 404);
        }
        return response()->json([
            "message" => 'success',
            "data" => new CustomerLocationResource($customerLoc)
        ], 200);
    }



    public function update(CustomerLocationRequest $request, $id)
    {
        $customerLoc = CustomerLocation::withTrashed()->find($id);

        if (!$customerLoc) {
            return response()->json(['message' => 'customer location not found.'], 404);
        }

        if ($customerLoc->trashed()) {
            $customerLoc->restore();
        }

        $customerLoc->update($request->validated());

        return response()->json([
            'message' => 'customer location updated successfully',
            'data' => new CustomerLocationResource($customerLoc)
        ], 200);
    }


    public function destroy(CustomerLocation $customerLoc)
    {
        if (!$customerLoc) {
            return response()->json(['message' => 'customer location not found.'], 404);
        }

        $customerLoc->delete();

        return response()->json([
            'message' => 'Customer Location Deleted Successfully'
        ], 200);
    }
}
