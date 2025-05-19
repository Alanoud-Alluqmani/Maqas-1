<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{

           public function __construct()
{
    $this->middleware('auth:sanctum')->only(['show']);
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
}
