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
        $this->middleware('auth:sanctum')->only(['show', 'update', 'updatePassword', 'updateEmail']);
    }

    public function show()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        return response()->json([
            'message' => 'success',
            'data' => [
                'user' => $user->load(['store']),
                'role' => $user->role()->get()
            ]
        ], 200);
    }



    public function update(UpdateUserRequest $request, User $user)
    {
        $authUser = Auth::user();
        if (!$authUser) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        $user->update($request->validated());

        return response()->json([
            'message' => 'success',
            'data' => [
                'user' => $user->load(['store']),
                'role' => $user->role()->get()
            ]
        ], 200);
    }


    public function updateEmail(UpdateEmailRequest $request, User $user)
    {

        $authUser = Auth::user();
        if (!$authUser) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $user['email_verified_at'] = null; 

        $validatedData = $request->validated();

        $user->update($validatedData);
        event(new Registered($user));

        return response()->json([
            'message' => 'Email changed successfully',
            'data' => $user->email
        ], 200);
    }


    public function updatePassword(UpdatePasswordRequest $request, User $user)
    {
        $authUser = Auth::user();
        if (!$authUser) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        $validated = $request->validated();

        if (!Hash::check($validated['old_password'], $authUser->password)) {

            return response()->json(['message' => 'Old Password Doesn\'t match!'], 422);
        }

        $user->update([
            'password' => Hash::make($validated['new_password'])
        ]);

        return response()->json(['message' => 'Password changed successfully'], 200);
    }
}
