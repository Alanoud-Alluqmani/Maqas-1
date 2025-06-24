<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{

public function handle($request, Closure $next, ...$roles)
{
    $user = Auth::user();

    if (!$user || !$user->role) {
        return response()->json(['message' => 'Unauthorized Role.'], 401);
    }

    if (!in_array($user->role->role, $roles)) {
        return response()->json(['message' => 'Access denied.'], 403);
    }

    return $next($request);
}

}