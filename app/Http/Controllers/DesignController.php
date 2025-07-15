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
use App\Http\Resources\DesignResource;
use Illuminate\Support\Str;

class DesignController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('role:Super Admin,Co-Admin')->only(['indexAdmin']);
        $this->middleware('role:Store Owner,Store Employee')->only(['indexPartner', 'store', 'update', 'destroy']);
    }



    public function indexAdmin(Store $store)
    {

        // $designs = Design::whereHas('feature_store', function ($query) use ($store) {
        //     $query->where('store_id', $store->id);
        // })->get();

        $designs = Design::whereHas(
            'feature_store',
            fn($query) =>
            $query->where('store_id', $store->id)
        )->with(['images', 'feature_store'])->get();

        return response()->json([
            'message' => 'success',
            'data' => DesignResource::collection($designs)
        ], 200);
    }


    public function indexPartner(Request $request)
    {
        $store = Auth::user()->store;
        $limit = $request->input('limit', 10);

        $designs = Design::whereHas('feature_store', function ($query) use ($store) {
            $query->where('store_id', $store->id);
        })->with(['feature_store', 'images'])
            ->paginate($limit);

        return response()->json([
            'message' => 'success',
            'data' => DesignResource::collection($designs)
        ], 200);
    }


    public function show(Design $design)
    {
        $store = Auth::user()->store;

        if ($design->feature_store->store_id !== $store->id) {
            return response()->json(['message' => 'This design does not belong to your store.'], 403);
        }

        if (!$design) {
            return response()->json(['message' => 'Design not found.'], 404);
        }

        $design = Design::with('images', 'feature_store')->find($design->id);

        return response()->json([
            "message" => 'success',
            "data" =>  new DesignResource($design),
        ], 200);
    }



    public function showStoreDesign(Request $request, Feature $feature)
    {
        $storeId = Auth::user()->store->id;
        $limit = $request->input('limit', 10);

        $designs = Design::whereHas('feature_store', function ($query) use ($feature, $storeId) {
            $query->where('feature_id', $feature->id)
                ->where('store_id', $storeId);
        })->with(['images', 'feature_store'])->paginate($limit);

        return response()->json([
            'message' => 'success',
            'data' => DesignResource::collection($designs)
        ], 200);
    }

    public function store(DesignRequest $request)
    {

        $store = Auth::user()->store;
        $featureStore = FeatureStore::where('id', $request->feature_store_id)->first();

        if (!$featureStore) {
            return response()->json([
                'message' => 'Invalid feature store ID'
            ], 404);
        }

        if ($featureStore->store_id !== $store->id) {
            return response()->json([
                'message' => 'You are not authorized to create a design for this feature'
            ], 403);
        }


        $design = Design::create(
            $request->validated(),
        );

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            // $imagename = $design['name_en'] . '.' . $image->getClientOriginalExtension();
            $imagename = Str::slug($design['name_en']) . '.' .  $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('image', $imagename, 'public');

            Image::create([
                'image' => $imagePath,
                'design_id' => $design->id
            ]);

            $design->load(['images', 'feature_store']);
        }

        return response()->json([
            "message" => "Design created successfully.",
            "data" => new DesignResource($design)
        ], 201);
    }


    public function update(UpdateDesignRequest $request, Design $design)
    {
        if (!$design) {
            return response()->json(['message' => 'Design not found'], 404);
        }

        $design->update($request->validated());

        return response()->json([
            'message' => 'Design updated successfully',
            "data" => new DesignResource($design)
        ], 200);
    }


    public function destroy(Design $design)
    {
        $store = Auth::user()->store;

        if (!$design) {
            return response()->json(['message' => 'Design not found.'], 404);
        }


        if (!$design->feature_store || $design->feature_store->store_id !== $store->id) {
            return response()->json(['message' => 'This design does not belong to your store.'], 403);
        }
        $design->delete();

        return response()->json([
            'message' => 'Design Deleted Successfully'
        ], 200);
    }
}
