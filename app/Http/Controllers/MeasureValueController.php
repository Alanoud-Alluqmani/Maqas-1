<?php

namespace App\Http\Controllers;

use App\Models\MeasureValue;
use App\Models\Measure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\MeasureValueResource;

class MeasureValueController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        //
    }




    public function store(Request $request)
    {
        $customer = Auth::user();

        $validated = $request->validate([
            'measure_id' => 'required|exists:measures,id',
            'values' => 'required|array',
            'values.*.measure_name_id' => 'required|exists:measure_names,id',
            'values.*.value' => 'required|numeric'
        ]);
        $measure = Measure::find($validated['measure_id']);

        if (!$measure) {
            return response()->json(['message' => 'Measure not found.'], 404);
        }

        if ($measure->customer_id !== $customer->id) {
            return response()->json(['message' => 'Unauthorized. This measure does not belong to you.'], 403);
        }

        foreach ($validated['values'] as $entry) {
            MeasureValue::updateOrCreate(
                [
                    'measure_id' => $validated['measure_id'],
                    'measure_name_id' => $entry['measure_name_id']
                ],
                [
                    'measure' => $entry['value']
                ]
            );
        }

        $measureValues = MeasureValue::with('measure_name', 'measure')
            ->where('measure_id', $validated['measure_id'])
            ->get();
        $measureValues->load('measure');

        return response()->json([
            'message' => 'Measure values saved successfully.',
            'data' => MeasureValueResource::collection($measureValues),

        ], 200);
    }


    // public function update(Request $request, MeasureValue $secondaryMeasure)
    // {
    //     $validated = $request->validate([
    //         'value' => 'required|numeric',
    //     ]);

    //     $measure = $secondaryMeasure->measure;

    //     if ($measure->customer_id !== Auth::id()) {
    //         return response()->json(['message' => 'Unauthorized.'], 403);
    //     }

    //     $secondaryMeasure->update(['measure' => $validated['value']]);

    //     return response()->json([
    //         'message' => 'Measure value updated successfully.',
    //         'data' => $secondaryMeasure,
    //     ]);
    // }

    public function update(Request $request, $id)
    {
        $customer = Auth::user();

        $validated = $request->validate([
            'measure' => 'required|numeric'
        ]);

        $measureValue = MeasureValue::with('measure')->find($id);

        $measureValue->update([
            'measure' => $validated['measure']
        ]);

        $measureValue->load('measure', 'measure_name');

        return response()->json([
            'message' => 'Measure value updated successfully.',
            'data' => new MeasureValueResource($measureValue)
        ], 200);
    }

    public function destroy(MeasureValue $secondaryMeasure)
    {
        $measure = $secondaryMeasure->measure;

        if ($measure->customer_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $secondaryMeasure->delete();

        return response()->json([
            'message' => 'Measure value deleted successfully.',
        ]);
    }
}
