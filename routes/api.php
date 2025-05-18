<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterMail;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;


//Auth
// Route::post('register', [AuthController::class, 'register']);
// Route::get('email', [AuthController::class, 'email']);
// Route::post('/api/login', [AuthController::class, 'login']);
// Route::post('logout', [AuthController::class, 'logout']);


Route::controller( AuthController::class)->group(function(): void{

    Route::post('register', 'ownerRegister')->name('ownerRegister');

    Route::post('login', 'login')->name('login');

    Route::post('logout', 'logout')->name('logout');

    Route::get('/email/verify/{id}',  'verifyEmail')->name('verify');

    Route::post('/forgot-password', 'forgotPassword')->name('password.email');

    Route::post('/reset-password', 'resetPassword')->name('password.update'); 
});

Route::get('/reset-password/{token}', function ($token) {
    return response()->json(['token' => $token]);
})->name('password.reset');


