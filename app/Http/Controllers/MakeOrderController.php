<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FeatureStore;
use App\Models\Order;
use App\Models\Status;
use App\Models\StoreLocation;
use App\Models\Design;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MakeOrderController extends Controller
{

    public function storeProduct(Request $request, $storeId)
{
    $limit = $request->input('limit', 10);
    if(!$storeId){
        return response()->json(['Store not found'], 404);
    }
    
     $features = FeatureStore::with(['designs', 'designs.images'])->where('store_id',$storeId)
     ->paginate($limit)->items();


    return response()->json([
        'message' => 'success',
        'data' => $features
    ], 200);
}



// public function store(Request $request)
//  {
//     $customer = Auth::user();
    
//     if($customer->id )

//  }



}
