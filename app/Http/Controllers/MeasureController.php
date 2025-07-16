<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Measure;
use Illuminate\Http\Request;
use App\Http\Resources\MeasureResource;
use Illuminate\Support\Facades\Auth;

class MeasureController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function store(Request $request)
    {
        $customer = Auth::user();
        $name = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $newMeasure = Measure::create([
            'name' => $name['name'],
            'customer_id' => $customer->id
        ]);

        return response()->json([
            'message' => 'measure created successfully',
            "data" => new MeasureResource($newMeasure)
        ]);
    }


    public function show(Measure $measure)
    {
        $customer = Auth::user();

        if ($measure->customer_id !== $customer->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $measure->load('secondary_measures.measure_name');

        return response()->json([
            'message' => 'Measure profile retrieved successfully',
            'data' =>new MeasureResource( $measure),
        ]);
    }

    public function update(Request $request, Measure $measure)
    {
        $customer = Auth::user();

        if ($measure->customer_id !== $customer->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $measure->update(['name' => $validated['name']]);

        return response()->json([
            'message' => 'Measure profile updated successfully',
            'data' => new MeasureResource( $measure),
        ]);
    }

    public function destroy(Measure $measure)
    {
        $customer = Auth::user();

        if ($measure->customer_id !== $customer->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $measure->secondary_measures()->delete(); 
        $measure->delete();

        return response()->json([
            'message' => 'Measure profile and values deleted successfully',
        ]);
    }
}
