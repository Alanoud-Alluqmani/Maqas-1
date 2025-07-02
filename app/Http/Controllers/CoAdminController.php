<?php

namespace App\Http\Controllers;

use App\Http\Requests\CoAdminRegisterRequest;
use App\Models\User;
use App\Models\Role;
use App\Models\Store;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CoAdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('role:Super Admin')->only(['coAdminRegister', 'deleteCoAdmin']);
        $this->middleware('role:Super Admin,Co-Admin')->only(['viewCoAdmins']);
    }


public function coAdminRegister(CoAdminRegisterRequest $request)
{
    $user = $request->validated();

        $store = Store::first();

        $user['store_id'] = $store->id;
        $user['legal'] = $store->legal;
        $user['product_category_id'] = $store->product_category_id;

        $role = Role::where('role', 'Co-Admin')->first();
        if (!$role) {
            return response()->json(['message' => 'Role "Co-Admin" not found in the database'], 404);
        }

        $user['role_id'] = $role->id;
        $user['password'] = Hash::make($user['password']);

        $createdUser = User::create($user);

        return response()->json([
            'message' => 'Co-admin registered successfully',
            'data' => $createdUser
        ], 200);
    }


public function viewCoAdmins(Request $request)
{
    $authUser = Auth::user();

    $limit = $request->input('limit', 10);

    if (!$authUser) {
        return response()->json(['message' => 'Unauthorized.'], 401);
    }

    $store = $authUser->store;

    if (!$store) {
        return response()->json(['message' => 'Store not found.'], 404);
    }

    $role = Role::where('role', 'Co-Admin')->first();

    if (!$role) {
        return response()->json(['message' => 'Co-Admin role not defined.'], 404);
    }

    $coAdmins = User::where('store_id', $store->id)
        ->where('role_id', $role->id)
        ->paginate($limit)->items();

    // if ($coAdmins->isEmpty()) {
    //     return response()->json(['message' => 'No Co-Admins found.'], 200);
    // }

    return response()->json([
        'message' => 'Co-Admins retrieved successfully.',
        'data' => $coAdmins
    ], 200 );
}

    public function deleteCoAdmin(User $user)
    {
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        if ($user->role->role !== 'Co-Admin') {
            return response()->json([
                'message' => 'Only users with the Co-Admin role can be deleted.'
            ], 403);
        }

        $user->delete();

        return response()->json(['message' => 'Co-Admin deleted successfully.'], 200);
    }

}