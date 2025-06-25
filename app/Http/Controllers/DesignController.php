<?php

namespace App\Http\Controllers;

use App\Models\Design;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Feature;
use App\Models\Image;
use App\Models\FeatureStore;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\DesignRequest;
use App\Http\Requests\UpdateDesignRequest;

class DesignController extends Controller
{

    public function __construct(){
        $this->middleware('auth:sanctum');// currently, all methods are protected by 
    }



    public function indexAdmin(Store $store)
    {
    $designs = Design::whereHas('feature_store', function ($query) use ($store) {
        $query->where('store_id', $store->id);
    })->get();

    return response()->json([
        'message' => 'success',
        'data' => $designs
    ], 200);
    }
   

       public function indexPartner()
    {
        $store = Auth::user()->store;
       $designs = Design::whereHas('feature_store', function ($query) use ($store) {
        $query->where('store_id', $store->id);
    })->get();

        return response()->json([
        'message' => 'success',
        'data' => $designs
    ], 200);
    }

    
    /**
     * Display the specified resource.
     */
    public function show(Design $design)
    {
        if (!$design) {
            return response()->json(['message' => 'Design not found.'], 404);
        }
          return response()->json([
            "message" => 'success', 
            "data" =>  $design
        ], 200);
    }




    
    /**
     * Display the specified resource.
     */
    public function showStoreDesign(Feature $feature)
     {
    $storeId = Auth::user()->store;
    $designs = Design::whereHas('feature_store', function ($query) use ($feature,  $storeId) {
        $query->where('feature_id', $feature->id)
        ->orWhere('store_id', $storeId);
    })->get();

    return response()->json([
        'message' => 'success',
        'data' => $designs
    ], 200);
    }


    

    /**
     * Store a newly created resource in storage.
     */
    // public function store(DesignRequest $request, FeatureStore $feature_store)
    // {
    //    $design = $feature_store->designs()->create($request->validated([
    //     'feature_store_id' => $feature_store['feature_store_id']]
    //    ));
        

    //     return response()->json([
    //         "message" => 'Design created successfully.', 
    //         "data" => $design
    //     ],201);

    // }

    public function store(DesignRequest $request )
{
    $design = Design::create(
        $request->validated(), // Validated data without parameters
    );

    if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagename = $design['name_en'] . '.' . $image->getClientOriginalExtension(); // Keeps the original extension
            $imagePath = $image->storeAs('image', $imagename, 'public');  
            $designImage = Image::create([
            'image' => $imagePath,
            'design_id' => $design->id
        ]);
         
        } // else {
        //     return response()->json(['message' => 'Image upload failed'], 400);
        // }

    

    return response()->json([
        "message" => "Design created successfully.",
        "data" => $design
    ], 201);
}



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDesignRequest $request, Design $design)
    {
        if(!$design){
            return response()->json(['message' => 'Design not found'], 404);
        }

         $design->update($request->validated());
    
        return response()->json([
            'message' => 'Design updated successfully',
            "data" => $design
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Design $design)
    {
         if (!$design) {
            return response()->json(['message' => 'Design not found.'], 404);
        }

        $design->delete();

        return response()->json([ // Return a JSON response indicating success
            'message' => 'Design Deleted Successfully'
        ],200);
    }
}
