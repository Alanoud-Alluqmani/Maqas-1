<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\StoreLocationController;
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

    Route::post('update-store/{id}', 'update')->name('updateStore');

    Route::get('destroy-store/{id}','destroy')->name('deleteStore');

});
//just for admin
  Route::get('show-stores', [StoreController::class,'index'])->name('showStores');

Route::controller( StoreLocationController::class)->group(function(): void{

     Route::get('show-store-loc/{id}','show')->name('showLoc');

     Route::post('store-loc/{id}', 'store')->name('storeLoc');

    Route::post('update-store-loc/{id}', 'update')->name('updateLoc');

    Route::get('destroy-store-loc/{id}','destroy')->name('deleteLoc');

});

//just for admin
  Route::get('show-stores-loc', [StoreLocationController::class,'index'])->name('showStoresLoc');

Route::get('/reset-password/{token}', function ($token) {
    return response()->json(['token' => $token]);
})->name('password.reset');



Route::controller( UserController::class)->group(function(){
    Route::get('/user', 'user')->name('user.profile');
});