<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterMail;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;


//Auth
Route::post('register', [AuthController::class, 'register']);
Route::get('email', [AuthController::class, 'email']);
Route::post('/api/login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);

//Route::get('email/verify', [AuthController::class, 'verifyEmail'])->name('verification.notice');

//Route::get('/email/verify', [AuthController::class, 'verifyEmail'])->middleware('auth')->name('verification.notice');

// Route::middleware(['auth:sanctum'])->group(function () {
//    Route::get('/auth/verify-email/{id}/{hash}', function ($id, $hash, Request $request) {
//         // Find user by ID
//         $user = User::find($id);
    
//         if (!$user) {
//             return response()->json(['message' => 'User not found.'], 404);
//         }
    
//         // Verify if the hash is correct
//         if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
//             return response()->json(['message' => 'Invalid verification link.'], 403);
//         }
    
//         // Mark email as verified
//         if (!$user->hasVerifiedEmail()) {
//             $user->markEmailAsVerified();
//         }
    
//         return response()->json(['message' => 'Email verified successfully!']);
//     })->middleware('signed')->name('verification.verify');


//     //resend Email
//     Route::post('/auth/resend-verification', function (Request $request) {
//         $user = User::where('email', $request->email)->first();
    
//         if (!$user) {
//             return response()->json(['message' => 'User not found.'], 404);
//         }
    
//         if ($user->hasVerifiedEmail()) {
//             return response()->json(['message' => 'Email already verified.'], 400);
//         }
    
//         $user->sendEmailVerificationNotification();
//         return response()->json(['message' => 'Verification link resent!']);
//     });
// });



// Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
//     $request->fulfill();
//     return response()->json(['message' => 'Email verified successfully. You can now log in.']);
// })->middleware(['auth:sanctum'])->name('verification.verify');


// Route::get('/email/verify/{id}', function ($id, Request $request) {
//     $user = User::find($id);

//     if (!$user) {
//         return response()->json(['message' => 'User not found'], 404);
//     }

//     if ($user->email_verified_at) {
//         return response()->json(['message' => 'Email is already verified']);
//     }

//     if ($request->header('Referer')) {
//         $user->email_verified_at = now();
//         $user->save();
//         return response()->json(['message' => 'Email verified successfully!']);
//     }

//     return response()->json(['message' => 'Email verified successfully!']);
// })->name('verification.verify');


Route::get('/email/verify/{id}', function ($id) {
    $user = User::find($id);

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    if ($user->email_verified_at) {
        return response()->json(['message' => 'Email is already verified']);
    }

    // Mark email as verified
    $user->email_verified_at = now();
    $user->save();

    return response()->json(['message' => 'Email verified successfully!']);
})->name('verification.verify');