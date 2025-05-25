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

    } // ADD IT TO API !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!


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
    public function view($id)
    {  
        $service_id = 'service_id_'.$id;
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
    } // EDIT IT TO API !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStatusRequest $request, Status $status)
    {
        
        $status->update([
            $request
        ]);

        $status->save();

        return response()->json([
            'message' => 'status updated Successfully', // Success message
            'data' => $status, // Include the created user data in the response
        ], 200);
    } // ADD IT TO API !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

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
