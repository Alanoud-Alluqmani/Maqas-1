<?php

namespace App\Http\Controllers;

use App\Models\Design;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Feature;
use App\Models\FeatureStore;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DesignController extends Controller
{

    public function __construct(){
        $this->middleware('auth:sanctum');// currently, all methods are protected by 
    }



    //     Route::get('show-store-designs/{store}', 'indexAdmin')->name('store.designs.show.admin');
    // Route::get('show-store-designs', 'indexPartner')->name('store.designs.show.partner');
    // Route::get('show-design/{design}', 'show')->name('store.design.show');
    // Route::get('show-feature-design/{feature}', 'showStoreDesign')->name('feature.design.show');
    // Route::post('store-desgin', 'store')->name('add.desgin');
    // Route::put('update-desgin', 'update')->name('update.desgin');
    // Route::delete('destroy-desgin/{desgin}', 'destroy')->name('destroy.desgin');

    /**
     * Display a listing of the resource.
     */
    // public function indexAdmin(Store $store)
    // {
    // //  $feature_store = Design::feature_store()->get();
    // //  echo $feature_store;
    //    //$designs = $feature_store->designs->get();
    //    $feature_store_id = DB::table('feature_store')
    //    ->where('store_id', $store->id)->get('id');

    //    //$designs = Design::whereIn('feature_store_id', $feature_store_id)->get();
    //     $designs = Design::with('feature_store')->get();
    //     return $designs;
    // }

    public function indexAdmin(Store $store)
    {
    $designs = Design::whereHas('feature_store', function ($query) use ($store) {
        $query->where('store_id', $store->id);
    })->get();

    return response()->json([
        'designs' => $designs
    ], 200);
    }
   


       public function indexPartner()
    {
        $store = Auth::user()->store;
       $designs = Design::with('feature_store')->get();
        return $designs;
    }

    
    /**
     * Display the specified resource.
     */
    public function show(Design $design)
    {
        //
    }


    
    /**
     * Display the specified resource.
     */
    public function showStoreDesign(Feature $feature)
    {
        //
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Design $design)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Design $design)
    {
        //
    }
}
