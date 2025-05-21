<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\StoreLocationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\PartneringOrderController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\SpecifyProductController;
use App\Http\Controllers\DesignController;
use App\Http\Requests\EmployeeRegisterRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterMail;
use App\Models\Design;
use App\Models\Feature;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\URL;


Route::controller( AuthController::class)->group(function(): void{

    Route::post('register', 'ownerRegister')->name('ownerRegister');

    Route::post('login', 'login')->name('login');

    Route::post('logout', 'logout')->name('logout');

    Route::get('verify-email/{id}/{hash}', 'emailVerify')->middleware('signed')->name('verification.verify');

    Route::post('resend-verification', 'resendEmailVerification')->name('verification.resend');

    // Route::post('/employee/register/{id}', 'employeeRegister')->middleware('signed')->name('employeeRegister');

    // Route::get('/generate-link/{store_id}', 'generateLink')->middleware('signed')->name('generateLink');

    Route::post('/forgot-password', 'forgotPassword')->name('password.email');

    Route::post('/reset-password', 'resetPassword')->name('password.update'); 

});

Route::controller( StoreController::class)->group(function(): void{

    Route::get('show-store/{id}','show')->name('showStore');

    Route::put('update-store/{id}', 'update')->name('updateStore');

    Route::delete('destroy-store/{id}','destroy')->name('deleteStore');

});
//just for admin
  Route::get('show-stores', [StoreController::class,'index'])->name('showStores');

Route::controller( StoreLocationController::class)->group(function(): void{

     Route::get('show-store-loc/{store}','showAll')->name('showAllLoc');

     Route::get('show-store-locs/{storeLoc}','show')->name('showLoc');

     Route::post('store-loc/{store}', 'store')->name('storeLoc');

    Route::put('update-store-loc/{storeLoc}', 'update')->name('updateLoc');

    Route::delete('destroy-store-loc/{storeLoc}','destroy')->name('deleteLoc');

});

//just for admin
  Route::get('show-stores-loc', [StoreLocationController::class,'index'])->name('showStoresLoc');

Route::get('/reset-password/{token}', function ($token) {
    return response()->json(['token' => $token]);
})->name('password.reset');



Route::controller( OrderController::class)->group(function(): void{

     Route::get('show-order/{order}','show')->name('showOrder');

     Route::get('show-orders','index')->name('showOrders');

    // Route::post('store-loc/{store}', 'store')->name('storeLoc');

   Route::post('update-order-status/{id}', 'update')->name('updateStatus');

    //Route::delete('destroy-store-loc/{id}','destroy')->name('deleteLoc');

});

Route::controller( StatusController::class)->group(function(): void{

     Route::get('show-status/{id}','show')->name('showStatus');

    // Route::get('show-orders','index')->name('showOrders');

     Route::post('add-status', 'store')->name('newStatus');

   //Route::post('update-order-status/{id}', 'update')->name('updateStatus');

    Route::delete('destroy-status/{status}','destroy')->name('deleteStatus');

});



Route::controller( UserController::class)->group(function(){
    Route::get('/show', 'show')->name('user.show');
    Route::put('update-user/{user}', 'update')->name('updateUser');
    Route::put('update-password/{user}', 'updatePassword')->name('updatePassword');
    Route::put('update-email/{user}', 'updateEmail')->name('updateEmail');

});


Route::apiResource('partnering-orders', PartneringOrderController::class)->except('store');

Route::apiResource('features', FeatureController::class)->except('store');

Route::controller( FeatureController::class)->group(function(): void{

    Route::post('features/{prod_catg}', 'store')->name('features.store');
    
    Route::get('features/{prod_catg}','showCategoryFeatures')->name('category.features');

     Route::post('add-status', 'store')->name('newStatus');

   //Route::post('update-order-status/{id}', 'update')->name('updateStatus');

    Route::get('destroy-status/{status}','destroy')->name('deleteStatus');

});


Route::post('features/{prod_catg}', [FeatureController::class, 'store'])->name('features.store');












Route::controller( SpecifyProductController::class)->group(function(){
    Route::get('show-store-features', 'index')->name('store.features.show');
    Route::get('show-store-feature/{feature}', 'show')->name('store.feature.show');
    Route::post('store-feature', 'store')->name('select.feature');
    Route::delete('destroy-feature/{feature}', 'destroy')->name('destroy.feature');

});


Route::controller(DesignController::class)->group(function(){
    Route::get('show-store-designs/{store}', 'indexAdmin')->name('store.designs.show.admin');
    Route::get('show-store-designs', 'indexPartner')->name('store.designs.show.partner');
    Route::get('show-design/{design}', 'show')->name('store.design.show');
    Route::get('show-feature-design/{feature}', 'showStoreDesign')->name('feature.design.show');
    Route::post('store-desgin', 'store')->name('add.desgin');
    Route::put('update-desgin', 'update')->name('update.desgin');
    Route::delete('destroy-desgin/{desgin}', 'destroy')->name('destroy.desgin');

});