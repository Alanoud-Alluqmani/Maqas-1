<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Measure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeasureController extends Controller
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

    /**
     * Display the specified resource.
     */
    public function show(Measure $measure)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Measure $measure)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Measure $measure)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Measure $measure)
    {
        //
    }
}
