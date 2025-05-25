<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use App\Http\Requests\ProductCategoryRequest;
use App\Http\Requests\UpdateProductCategoryRequest;

class ProductCategoryController extends Controller
{

    public function __construct(){
        $this->middleware('auth:sanctum')->except('index');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categs = ProductCategory::all(); 
        if (!$categs){
            return response()->json([
            'message' => 'no categories found'
        ], 404);
        } else
        return response()->json([
            'message' => 'categories found',
            'data' => $categs // Return the products in JSON format
        ], 200);
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductCategoryRequest $request)
    {
        $validated = $request->validated();

        $filePath = "";

        if ($request->hasFile('icon')){
            $file = $request->file('icon');
            $filename = $file->getClientOriginalName() . '.' . $file->getClientOriginalExtension(); // Keeps the original extension
            $filePath = $file->storeAs('icon', $filename, 'public');
        }

        $categ = ProductCategory::create([
            'icon' => $filePath, // Store uploaded file path
            'name_ar' => $validated['name_ar'],
            'name_en' => $validated['name_en'],
        ]);

        return response()->json([
            'message' => 'Product Category Created Successfully', // Success message
            'data' => $categ, // Include the created user data in the response
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductCategoryRequest $productCategory)
    {
        if (!$productCategory){
            return response()->json([
            'message' => 'category not found'
        ], 404);
        } else
        return response()->json([
            'message' => 'category found',
            'data' => $productCategory // Return the products in JSON format
        ], 200);
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductCategoryRequest $request, ProductCategory $productCategory)
    {
        $validated = $request->validated();

        if ($request->hasFile('icon')){
            $file = $request->file('icon');
            $filename = $file->getClientOriginalName() . '.' . $file->getClientOriginalExtension(); // Keeps the original extension
            $filePath = $file->storeAs('icon', $filename, 'public');

            $productCategory->update([
            'icon' => $filePath, // Store uploaded file path
            ]);
        }

        $productCategory->update([
            $validated
        ]);

        $productCategory->save();

        return response()->json([
            'message' => 'Product Category updated Successfully', // Success message
            'data' => $productCategory, // Include the created user data in the response
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductCategory $productCategory)
    {
        $productCategory->delete();

        return response()->json([ // Return a JSON response indicating success
            'message' => 'product category Deleted Successfully'
        ], 200);
    }
}
