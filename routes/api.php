<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


//Auth
Route::post('register', [AuthController::class, 'register']);
Route::post('/api/login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);


Route::controller( AuthController::class)->group(function(): void{

    Route::post('register', 'ownerRegister')->name('ownerRegister');

    Route::post('login', 'login')->name('login');

    Route::post('logout', 'logout')->name('logout');

    Route::get('email',  'email')->name('email');
});