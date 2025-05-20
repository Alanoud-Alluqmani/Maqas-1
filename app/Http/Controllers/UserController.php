<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateEmailRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class UserController extends Controller
{

           public function __construct()
{
    $this->middleware('auth:sanctum')->only(['show','update', 'updatePassword' , 'updateEmail']);
}


    public function show(){

        /** @var \App\Models\User $user */  // somehow explicitly teeling laravel what this variable
                                            // is an instance of, because somehow it acts dump like
                                            // it does not know...
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
        return response()->json([
            'data' => $user->load(['store']),
            'role' => $user->role()->get()
        ]);

    }



    public function update(UpdateUserRequest $request, User $user){
        $authUser = Auth::user();
        if (!$authUser) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
        $user->update($request->validated());

         return response()->json([
            'data' => $user->load(['store']),
            'role' => $user->role()->get()
        ]); 
    }

    // public function updateEmail(UpdateEmailRequest $request, User $user){
    //     $authUser = Auth::user();
    //     if (!$authUser) {
    //         return response()->json(['error' => 'Unauthenticated'], 401);
    //     }
    //     $user->update($request->validated());
    //      return response()->json([
    //         'Message' => 'Email changed successfully',
    //         'New email'=> $user->email
    //     ], 200);
    // }


    public function updateEmail(UpdateEmailRequest $request, User $user){
    $authUser = Auth::user();
    if (!$authUser) {
        return response()->json(['error' => 'Unauthenticated'], 401);
    }
    $user['email_verified_at'] = null; // Set email_verified_at to null
    $validatedData = $request->validated();

    // if ( $user['email'] === $validatedData['email']) {
    //     return response()->json([
    //         'Message' => 'The new email is the same as the current email. No changes made.'
    //     ], 400); 
    // }

    $user->update($validatedData);
    event(new Registered($user));

    return response()->json([
        'Message' => 'Email changed successfully',
        'New email' => $user->email
    ], 200);
}


    public function updatePassword(UpdatePasswordRequest $request, User $user){
        $authUser = Auth::user();
        if (!$authUser) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
        $validated = $request->validated();

         //Match The Old Password
        if(!Hash::check($validated['old_password'], $authUser->password)){
        
        return response()->json(['error' => 'Old Password Doesn\'t match!']);
        }

        $user->update([
            'password' => Hash::make($validated['new_password'])
        ]);

        return response()->json(['Message' => 'Password changed successfully'], 200);
    }
    

    
}
