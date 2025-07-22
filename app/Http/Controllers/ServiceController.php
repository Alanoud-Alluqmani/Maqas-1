<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\StoreResource;

class ServiceController extends Controller
{
    //test2

    public function index()
    {
        $services = Service::all();

        if (!$services) {
            return response()->json([
                'message' => 'no services found for'
            ], 404);
        }
        return response()->json([
            'message' => 'services found for',
            'data' => $services
        ], 200);
    }



    public function listStoreServices(Store $store)
    {
        $store->load('services');

        return response()->json([
            'message' => 'Store services retrieved successfully.',
            'data' => $store->services
        ], 200);
    }


    public function setStoreServices(Request $request)
    {
        $authUser = Auth::user();

        if (!$authUser) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }


        $store = $authUser->store;
        $validated = $request->validate([
            'service_ids' => 'required|array',
            'service_ids.*' => 'exists:services,id'
        ]);


        $store->services()->sync($validated['service_ids']);

        return response()->json([
            'message' => 'Services assigned to store successfully.'
        ], 200);
    }



    public function show(Service $service)
    {
        if (!$service) {
            return response()->json([
                'message' => 'service not found'
            ], 404);
        }
        return response()->json([
            'message' => 'statuses found',
            'data' => $service
        ], 200);
    }



}
