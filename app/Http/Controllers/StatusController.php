<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StatusRequest;
use App\Http\Requests\UpdateStatusRequest;

class StatusController extends Controller
{
    public function __construct(){
        $this->middleware('auth:sanctum');// currently, all methods are protected by 
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $statuses = Status::all();

        if (!$statuses){
            return response()->json([
            'message' => 'no statuses found'
        ], 404);
        } else
        return response()->json([
            'message' => 'statuses found',
            'data' => $statuses 
        ], 200);


    } 

    /**
     * Store a newly created resource in storage.
     */
    public function store(StatusRequest $request)
    {
        $status = Status::create( $request->validated());
        return response()->json([
            'message' => 'status created successfully',
            "data" => $status
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Status $status)
    {  
        $service_id = 'service_id_'.$status->id;
        $statuses = Status::where($service_id, true)->get();
        
        if (!$statuses){
            return response()->json([
            'message' => 'no statuses found'
        ], 404);
        } else
        return response()->json([
            'message' => 'statuses found',
            'data' => $statuses 
        ], 200);
    } 


    public function update(UpdateStatusRequest $request, Status $status)
    {
        if(!$status){
            return response()->json(['message' => 'status not found'], 404);
        }
         $status->update($request->validated());

        return response()->json([
            'message' => 'status updated successfully',
            "data" => $status
        ], 200);
        
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Status $status)
    {
        $status->delete();

        return response()->json([ // Return a JSON response indicating success
            'message' => 'Status Deleted Successfully'
        ], 200);
    }
}
