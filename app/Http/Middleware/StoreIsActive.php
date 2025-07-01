<?php
namespace App\Http\Middleware;
 
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreIsActive
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user || !$user->store) {
            return response()->json(['message' => 'Store not found.'], 404);
        }

        if (!$user->store->is_active) {
            return response()->json(['message' => 'Store is not active.'], 403);
        }

        return $next($request);
    }
}