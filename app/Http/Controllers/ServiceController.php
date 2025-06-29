<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::all();

        if (!$services){
            return response()->json([
            'message' => 'no services found for'
        ], 404);
        } else
        return response()->json([
            'message' => 'services found for',
            'data' => $services // Return the products in JSON format
        ], 200);

    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }


    public function setStoreServices(Request $request)
{
    $store = Auth::user()->store;

    $validated = $request->validate([
        'service_ids' => 'required|array',
        'service_ids.*' => 'exists:services,id'
    ]);

    // Sync selected services for the store
    $store->services()->sync($validated['service_ids']);

    return response()->json([
        'message' => 'Services assigned to store successfully.'
    ]);
}

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        if (!$service){
            return response()->json([
            'message' => 'service not found'
        ], 404);
        } else
        return response()->json([
            'message' => 'statuses found',
            'data' => $service 
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        //
    }
}
