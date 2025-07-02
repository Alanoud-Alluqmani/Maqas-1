<?php

namespace App\Http\Controllers;

use App\Models\MeasureValue;
use Illuminate\Http\Request;

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
        $validated = $request->validate([
            'measure_id' => 'required|exists:measures,id',
            'values' => 'required|array',
            'values.*.measure_name_id' => 'required|exists:measure_names,id',
            'values.*.value' => 'required|numeric'
        ]);

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

        return response()->json([
            'message' => 'Measure values saved successfully.',
            'data' => $measureValues
        ], 200);
    }


    public function update(Request $request, MeasureValue $secondaryMeasure)
    {
        //
    }

    public function destroy(MeasureValue $secondaryMeasure)
    {
        //
    }
}
