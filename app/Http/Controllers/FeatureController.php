<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use App\Http\Requests\FeatureRequest;
use App\Http\Requests\UpdateFeatureRequest;

class FeatureController extends Controller
{

    public function __construct(){
        //$this->middleware('auth:sanctum');// currently, all methods are protected by 
        $this->middleware(['auth:sanctum', 'role:Super Admin, Co-Admin'])->only(['store', 'update' , 'destroy']);
    
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);

        $features = Feature::paginate($limit)->items(); 

          return response()->json([
            "message" => 'success', 
            "data" =>  $features
        ], 200);
    }



    public function store(FeatureRequest $request, ProductCategory $prod_catg)
    {
         if (!$prod_catg) {
        return response()->json([
            "message" => 'Product Category not found',
        ], 404);
    }

    $data = $request->validated();

    $existing = Feature::onlyTrashed()
        ->where('name_ar', $data['name_ar'])
        ->where('name_en', $data['name_en'])
        ->where('product_category_id', $prod_catg->id)
        ->first();

    if ($existing) {
        $existing->restore();
        $existing->update($data); 
        return response()->json([
            "message" => 'Feature restored successfully.',
            "data" => $existing
        ], 200);
    }

    $feature = $prod_catg->features()->create($data);

    return response()->json([
        "message" => 'Feature created successfully.',
        "data" => $feature
    ], 201);
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

    public function viewCategoryFeatures(Request $request, ProductCategory $prod_catg)
    {

        $limit = $request->input('limit', 10);

        if (!$prod_catg) {
            return response()->json(['message' => 'Product category not found.'], 404);
        }
        if (!$prod_catg->features) {
            return response()->json(['message' => 'Product category features not found.'], 404);
        }
        $pc = $prod_catg->features()->paginate($limit)->items();
          return response()->json([
            "message" => 'success', 
            "data" =>  $pc
        ], 200);
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFeatureRequest $request, Feature $feature)
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
