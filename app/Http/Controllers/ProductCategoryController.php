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
        return $categs; 
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
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductCategoryRequest $productCategory)
    {
        return $productCategory;
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
            'data' => $$productCategory, // Include the created user data in the response
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductCategory $productCategory)
    {
        $productCategory->delete();

        return response()->json([ // Return a JSON response indicating success
            'message' => 'product category Deleted Successfully'
        ]);
    }
}
