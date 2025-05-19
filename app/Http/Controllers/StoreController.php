<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRequest;

class StoreController extends Controller
{

    public function __construct(){
        $this->middleware('auth:sanctum')->only(['addEmployee']);
    }

    public function addEmployee(Request $request){

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $stores = Store::all(); 
        return $stores; 
    }

    // /**
    //  * Show the form for creating a new resource.
    //  */
    // public function create()
    // {
    //     //
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  */
    // public function store(StoreRequest $request)
    // {
    //   $user = $request->validated();
    //     $user = Store::store($user);
    // }


        
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $store = Store::findOrFail($id);
        return $store;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Store $store)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreRequest $request, $id)
    {
         // Validate and retrieve the data
     $store = Store::findOrFail($id);
      $store->update($request->validated()); // Update the product with validated data
        return response()->json([
            'store' => $store, // Return the updated product
            'message' => 'Store updated successfully' // Success message
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
     $store = Store::findOrFail($id);
    $store->delete();

        return response()->json([ // Return a JSON response indicating success
            'message' => 'Store Deleted Successfully'
        ]);
    }
}
