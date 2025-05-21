<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use App\Http\Requests\FeatureRequest;

class FeatureController extends Controller
{

    public function __construct(){
        $this->middleware('auth:sanctum');// currently, all methods are protected by 
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $features = Feature::all(); 
        return $features;
    }


    

    /**
     * Store a newly created resource in storage.
     */
    public function store(FeatureRequest $request, ProductCategory $prod_catg)
    {
        $feature = $prod_catg->features()->create($request->validated());
        
        return response()->json([
            "message" => 'feature created successfully.', 
            "data" => $feature
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Feature $feature)
    {
        return $feature;
    }

    public function showCategoryFeatures(ProductCategory $prod_catg)
    {
        return $prod_catg->features;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FeatureRequest $request, Feature $feature)
    {
        $feature->update($request->validated()); // Update the product with validated data
        return response()->json([
            'feature' => $feature, 
            'message' => 'feature updated successfully' // Success message
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Feature $feature)
    {
        $feature->delete();

        return response()->json([ // Return a JSON response indicating success
            'message' => 'feature Deleted Successfully'
        ]);
    }
}
