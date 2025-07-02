<?php

namespace App\Http\Controllers;

use App\Models\MeasureName;
use Illuminate\Http\Request;

class MeasureNameController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('role:Super Admin,Co-Admin')->only(['store', 'store', 'update', 'destroy']);
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);

        $measures = MeasureName::paginate($limit)->items();

        return response()->json([
            "message" => 'success',
            "data" =>  $measures
        ], 200);
    }


    public function store(Request $request)
    {
        //
    }


    public function update(Request $request, MeasureName $measureName)
    {
        //
    }

    public function destroy(MeasureName $measureName)
    {
        //
    }
}
