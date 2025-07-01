<?php

namespace App\Http\Controllers;

use App\Models\MeasureValue;
use Illuminate\Http\Request;

class MeasureValueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
//     public function store(Request $request)
//     {
//         $validated = $request->validate([
//         'measure_id' => 'required|exists:measures,id',
//         'values' => 'required|array',
//         'values.*.measure_name_id' => 'required|exists:measure_names,id',
//         'values.*.value' => 'required|numeric'
//     ]);


//    foreach ($validated['values'] as $entry) {
        
//         MeasureValue::updateOrCreate(
//     [
//         'measure_id' => $validated['measure_id'],
//         'name' => $entry['measure_name_id']
//     ],
//     [
//         'measure' => $entry['value']
//     ]
// );
//     }

    

//     return response()->json([
//         'message' => 'Secondary measure values saved successfully.',
//         'data' => ''
    
//     ], 200);

//     }

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

 

 

    /**
     * Display the specified resource.
     */
    public function show(MeasureValue $MeasureVlaue)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MeasureValue $MeasureVlaue)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MeasureValue $secondaryMeasure)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MeasureValue $secondaryMeasure)
    {
        //
    }
}
