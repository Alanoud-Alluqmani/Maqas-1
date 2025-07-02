<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use App\Http\Requests\ProductCategoryRequest;
use App\Http\Requests\UpdateProductCategoryRequest;

class ProductCategoryController extends Controller
{

    public function __construct()
    {
       // $this->middleware('auth:sanctum');
        $this->middleware('role:Super Admin,Co-Admin')->only(['store', 'update', 'destroy']);
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);

        $categs = ProductCategory::paginate($limit)->items();
        if (!$categs) {
            return response()->json([
                'message' => 'no categories found'
            ], 404);
        } else
            return response()->json([
                'message' => 'categories found',
                'data' => $categs
            ], 200);
    }


    public function store(ProductCategoryRequest $request)
    {
        $validated = $request->validated();

        $filePath = "";

        if ($request->hasFile('icon')) {
            $file = $request->file('icon');
            $filename = $validated['name_en']  . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('icon', $filename, 'public');
        } else {
            return response()->json(['message' => 'File upload failed'], 400);
        }

        $categ = ProductCategory::create([
            'icon' => $filePath,
            'name_ar' => $validated['name_ar'],
            'name_en' => $validated['name_en'],
        ]);

        return response()->json([
            'message' => 'Product Category Created Successfully',
            'data' => $categ,
        ], 201);
    }


    public function show(ProductCategory $product_category)
    {
        if (!$product_category) {
            return response()->json([
                'message' => 'category not found'
            ], 404);
        }
        return response()->json([
            'message' => 'category found',
            'data' => $product_category
        ], 200);
    }


    public function update(UpdateProductCategoryRequest $request, ProductCategory $productCategory)
    {
        $validated = $request->validated();

        if ($request->hasFile('icon')) {
            $file = $request->file('icon');
            $filename = $file->getClientOriginalName() . '.' . $file->getClientOriginalExtension(); // Keeps the original extension
            $filePath = $file->storeAs('icon', $filename, 'public');

            $productCategory->update([
                'icon' => $filePath,
            ]);
        }

        $productCategory->update(
            $validated
        );

        $productCategory->save();

        return response()->json([
            'message' => 'Product Category updated Successfully',
            'data' => $productCategory,
        ], 200);
    }


    public function destroy(ProductCategory $product_category)
    {
        if (!$product_category) {
            return response()->json(['message' => 'product category not found.'], 404);
        }
        $product_category->delete();

        return response()->json([
            'message' => 'product category Deleted Successfully'
        ], 200);
    }
}
