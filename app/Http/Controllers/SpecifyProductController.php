<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Store;
use App\Models\FeatureStore;
use App\Models\Feature;
use App\Http\Requests\SpecifyProductRequest;
use Illuminate\Support\Facades\DB;

class SpecifyProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('role:Store Owner,Store Employee')->only(['store', 'storeProduct', 'destroy']);
    }

    public function index(Request $request)
    {
        $store = Auth::user()->store;
        $limit = $request->input('limit', 10);
        $features = $store->features()->paginate($limit)->items();
        if (!$features) {
            return response()->json([
                'message' => 'no features found for this store'
            ], 404);
        } else
            return response()->json([
                'message' => 'features found for this store',
                'data' => $features
            ], 200);
    }



    public function unselectedFeatures(Request $request)
{
    $store = Auth::user()->store;
    $limit = $request->input('limit', 10);

    
    $selectedFeatureIds = $store->features()->pluck('features.id')->toArray();

    
    $features = Feature::whereNotIn('id', $selectedFeatureIds)
        ->paginate($limit)
        ->items();

    if (empty($features)) {
        return response()->json([
            'message' => 'No unselected features found for this store.'
        ], 404);
    }

    return response()->json([
        'message' => 'Unselected features retrieved successfully.',
        'data' => $features
    ], 200);
}

    public function show(FeatureStore $feature)
    {
        $store = Auth::user()->store;

        if (!$feature) {
            return response()->json(['message' => 'feature not found'], 404);
        }

        if ($feature->store_id !== $store->id) {
            return response()->json(['message' => 'This feature does not belong to your store.'], 403);
        }

        $featurestore = Feature::find($feature->feature_id);
        $storeInfo = Store::find($feature->store_id);


        return response()->json([
            'message' => 'feature found',
            'store feature' => $featurestore,
            'feature' => $feature,
            'store' => $storeInfo

        ], 200);
    }



    public function store(SpecifyProductRequest $request)
    {
        $store = Auth::user()->store;

        $feature = $request->validated();
        $featureId = $feature['id'];

        if ($store->features()->wherePivot('feature_id', $featureId)->exists()) {

            $pivot = FeatureStore::withTrashed()
                ->where('store_id', $store->id)
                ->where('feature_id', $featureId)->first();

            if ($pivot && $pivot->trashed()) {
                $pivot->restore();
                return response()->json(['message' => 'Feature restored successfully'], 200);
            } else {
                return response()->json(['message' => 'Feature is selected before'], 403);
            }
        }
        $store->features()->attach($featureId);

        $savedFeature = Feature::with('product_category')->find($featureId);

        return response()->json([
            'message' => 'Feature selected successfully',
            'data' => $savedFeature,
        ], 201);
    }

    public function destroy(FeatureStore $feature)
    {
        $store = Auth::user()->store;

        if (!$feature) {
            return response()->json(['message' => 'Feature not found.'], 404);
        }

        if ($feature->store_id !== $store->id) {
            return response()->json(['message' => 'This feature does not belong to your store.'], 403);
        }

        $feature->delete();

        return response()->json([
            'message' => 'Feature Deleted Successfully'
        ], 200);
    }


    public function storeProduct(Request $request, $storeId)
    {
        $limit = $request->input('limit', 10);
        if (!$storeId) {
            return response()->json(['Store not found'], 404);
        }

        $features = FeatureStore::with(['designs', 'designs.images'])->where('store_id', $storeId)
            ->paginate($limit)->items();


        return response()->json([
            'message' => 'success',
            'data' => $features
        ], 200);
    }
}
