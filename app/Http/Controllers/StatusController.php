<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StatusRequest;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StatusRequest $request)
    {
        $status = Status::create( $request->validated());
        return response()->json([
             "new status" => $status
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {  
        $service_id = 'service_id_'.$id;
        $statuses = Status::where($service_id, true)->get();
        return response()->json([
        'status' => $statuses
    ]);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Status $status)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Status $status)
    {
        $status->delete();

        return response()->json([ // Return a JSON response indicating success
            'message' => 'Status Deleted Successfully'
        ]);
    }
}
