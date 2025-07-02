<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Measure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeasureController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function store(Request $request)
    {
        //$customer = Auth::user();
        $name = $request->validate([
            //'customer_id' =>'required|integer|exists:customers,id',
            'name' => 'required|string|max:255'
        ]);

        //$customer_id = $customer->id;

        $newMeasure = Measure::create([
            'name' => $name['name'],
            // 'customer_id' => $customer->id
            //'customer_id' => '11'
        ]);

        return response()->json([
            'message' => 'measure created successfully',
            "data" => $newMeasure
        ]);
    }


    public function show(Measure $measure)
    {
        //
    }


    public function update(Request $request, Measure $measure)
    {
        //
    }


    public function destroy(Measure $measure)
    {
        //
    }
}
