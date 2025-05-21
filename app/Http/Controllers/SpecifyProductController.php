<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Store;
use App\Models\FeatureStore;
use App\Http\Requests\SpecifyProductRequest;
use Illuminate\Support\Facades\DB;

class SpecifyProductController extends Controller
{
    public function __construct(){
        $this->middleware('auth:sanctum');// currently, all methods are protected by 
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $store = Auth::user()->store;
        $features = $store->features;
        return $features;
    }

     /**
     * Display the specified resource.
     */
    public function show(FeatureStore $feature)
    {
        $store_id = Auth::user()->store->id;
        // $store = $user->store->id;

        $something = DB::table('feature_store')
        ->where('store_id', $store_id)
        ->where('feature_id', $feature->id)
        ->get();
        // ->load('designs');

        return $something;
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(SpecifyProductRequest $request)
    {
        $store = Auth::user()->store;
       
         $feature = $request->validated();

         //if($feature['id'] )

          if (!$store->features()->wherePivot('feature_id', $feature['id'])->exists()) {
        $store->features()->attach($feature['id']);
    }

   

    // $store->features()->attach(['feature_id'=>$feature['id']]); 
    // $store->save(); 

    return response()->json([
        'Feature' => $store,
        'message' => 'Feature selected successfully'
    ],200);
    }


   
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FeatureStore $feature)
    {
        $feature->delete();

        return response()->json([ // Return a JSON response indicating success
            'message' => 'feature Deleted Successfully'
        ]);
    }
}
