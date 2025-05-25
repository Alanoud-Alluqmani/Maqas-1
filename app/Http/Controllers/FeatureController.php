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

          return response()->json([
            "message" => 'success', 
            "data" =>  $features
        ], 200);
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
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Feature $feature)
    {
        
        if (!$feature) {
            return response()->json(['message' => 'Feature not found.'], 404);
        }
          return response()->json([
            "message" => 'success', 
            "data" =>  $feature
        ], 200);
    }

    public function viewCategoryFeatures(ProductCategory $prod_catg)
    {

        if (!$prod_catg) {
            return response()->json(['message' => 'Product category not found.'], 404);
        }
        if (!$prod_catg->features) {
            return response()->json(['message' => 'Product category features not found.'], 404);
        }
         
          return response()->json([
            "message" => 'success', 
            "data" =>  $prod_catg->features
        ], 200);
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FeatureRequest $request, Feature $feature)
    {
        if (!$feature) {
            return response()->json(['message' => 'Feature not found.'], 404);
        }

        $feature->update($request->validated()); // Update the product with validated data
       
        return response()->json([
            'message' => 'feature updated successfully' ,
            'data' => $feature
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Feature $feature)
    {
        if (!$feature) {
            return response()->json(['message' => 'Feature not found.'], 404);
        }

        $feature->delete();

        return response()->json([ // Return a JSON response indicating success
            'message' => 'Feature Deleted Successfully'
        ],200);
    }
}
