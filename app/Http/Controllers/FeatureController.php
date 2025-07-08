<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use App\Http\Requests\FeatureRequest;
use App\Http\Requests\UpdateFeatureRequest;
use Illuminate\Support\Facades\Auth;

class FeatureController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('role:Super Admin,Co-Admin')->only(['store', 'update', 'destroy']);
    }


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

        $duplicate = Feature::where('name_ar', $data['name_ar'])
            ->where('name_en', $data['name_en'])
            ->where('product_category_id', $prod_catg->id)
            ->first();

        if ($duplicate) {
            return response()->json([
                "message" => 'Feature already exists.',
                "data" => $duplicate
            ], 409);
        }

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

    // public function viewCategoryFeatures(Request $request, ProductCategory $prod_catg)
    // {

    //     $limit = $request->input('limit', 10);

    //     if (!$prod_catg) {
    //         return response()->json(['message' => 'Product category not found.'], 404);
    //     }
    //     if (!$prod_catg->features) {
    //         return response()->json(['message' => 'Product category features not found.'], 404);
    //     }
    //     $pc = $prod_catg->features()->paginate($limit)->items();
    //     return response()->json([
    //         "message" => 'success',
    //         "data" =>  $pc
    //     ], 200);
    // }

    public function viewCategoryFeatures(Request $request)
{
    $limit = $request->input('limit', 10);

    $store = Auth::user()->store;

    if (!$store || !$store->product_category_id) {
        return response()->json(['message' => 'Store or product category not found.'], 404);
    }

    $prod_catg = \App\Models\ProductCategory::find($store->product_category_id);

    if (!$prod_catg) {
        return response()->json(['message' => 'Product category not found.'], 404);
    }

    $features = $prod_catg->features()->paginate($limit)->items();

    return response()->json([
        "message" => 'success',
        "data" => $features
    ], 200);
}


    public function update(UpdateFeatureRequest $request, Feature $feature)
    {
        if (!$feature) {
            return response()->json(['message' => 'Feature not found.'], 404);
        }

        $feature->update($request->validated());

        return response()->json([
            'message' => 'feature updated successfully',
            'data' => $feature
        ], 200);
    }


    public function destroy(Feature $feature)
    {
        if (!$feature) {
            return response()->json(['message' => 'Feature not found.'], 404);
        }

        $feature->delete();

        return response()->json([
            'message' => 'Feature Deleted Successfully'
        ], 200);
    }
}
