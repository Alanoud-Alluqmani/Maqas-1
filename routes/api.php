<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterMail;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;


Route::controller( AuthController::class)->group(function(): void{

    Route::post('register', 'ownerRegister')->name('ownerRegister');

    Route::post('login', 'login')->name('login');

    Route::post('logout', 'logout')->name('logout');

    Route::get('verify-email/{id}/{hash}', 'emailVerify')->middleware('signed')->name('verification.verify');

    Route::post('resend-verification', 'resendEmailVerification')->name('verification.resend');

});

