<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\StoreLocationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StatusController;
use App\Http\Requests\EmployeeRegisterRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterMail;
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

    Route::get('destroy-store/{id}','destroy')->name('deleteStore');

});
//just for admin
  Route::get('show-stores', [StoreController::class,'index'])->name('showStores');

Route::controller( StoreLocationController::class)->group(function(): void{

     Route::get('show-store-loc/{store}','showAll')->name('showAllLoc');

     Route::get('show-store-locs/{storeLoc}','show')->name('showLoc');

     Route::post('store-loc/{store}', 'store')->name('storeLoc');

    Route::put('update-store-loc/{storeLoc}', 'update')->name('updateLoc');

    Route::get('destroy-store-loc/{storeLoc}','destroy')->name('deleteLoc');

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

    //Route::get('destroy-store-loc/{id}','destroy')->name('deleteLoc');

});

Route::controller( StatusController::class)->group(function(): void{

     Route::get('show-status/{id}','show')->name('showStatus');

    // Route::get('show-orders','index')->name('showOrders');

     Route::post('add-status', 'store')->name('newStatus');

   //Route::post('update-order-status/{id}', 'update')->name('updateStatus');

    Route::get('destroy-status/{status}','destroy')->name('deleteStatus');

});



Route::controller( UserController::class)->group(function(){
    Route::get('/show', 'show')->name('user.show');
    Route::put('update-user/{user}', 'update')->name('updateUser');
    Route::put('update-password/{user}', 'updatePassword')->name('updatePassword');
    Route::put('update-email/{user}', 'updateEmail')->name('updateEmail');

});