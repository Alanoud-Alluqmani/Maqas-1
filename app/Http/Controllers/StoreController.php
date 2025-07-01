<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use App\Http\Requests\StoreRequest;
use App\Http\Requests\AddEmployeeRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; 

class StoreController extends Controller
{

    public function __construct(){
        //$this->middleware('auth:sanctum');// currently, all methods are protected by 
        // login, but will this affect the customer?
        // $this->middleware('super admin')->only(['index', 'destroy']);
        // // the index function too is supposed to be used to the customer...
        // $this->middleware('co admin')->only(['index', 'destroy']);
        // $this->middleware('store owner')->only(['destroy', 'update']);
        // $this->middleware('store employee')->only(['']);
        $this->middleware(['auth:sanctum', 'role:Store Owner', 'store.active'])->only(['addEmployee', 'deleteEmployee' ]);
    
    }

    public function addEmployee(AddEmployeeRequest $request){

           $authUser = Auth::user();

        // if ( !$authUser->role || $authUser->role->role !== 'Store Owner') {
        //     return response()->json([
        //     'message' => 'Only the store owner can add a employee.'
        //     ], 403);
        //     }
    $store = $authUser->store;

        
          if (!$store) {
            return response()->json(['message' => 'Store not found.'], 404);
        }

        // if(!$store->is_active){
        //     return response()->json(['message' => 'Store is not active.'], 404);
        // }
       
        $user = $request->validated();
        // $store = Store::first();
        $user['store_id'] = $store->id;
        $user['legal'] = $store->legal;
        $user['product_category_id'] = $store->product_category_id;
        //$user['partnering_order'] = $store->partnering_order;

         $role = Role::where('role', 'Store Employee')->first();
    if (!$role) {
        return response()->json(['message' => 'Role "Store Employee" not found in the database'], 404);
    }

    $user['role_id'] = $role->id;
    $user['password'] = Hash::make($user['password']);

    $createdUser = User::create($user);


    return response()->json([
        'message' => 'Employee registered successfully',
        'data' => $createdUser
    ], 200);

    }


    public function viewEmployees(Request $request)
{
     $limit = $request->input('limit', 10);

    $authUser = Auth::user();

    if (!$authUser) {
        return response()->json(['message' => 'Unauthorized.'], 401);
    }


    $store = $authUser->store;

    if (!$store) {
        return response()->json(['message' => 'Store not found.'], 404);
    }

    $role = Role::where('role', 'Store Employee')->first();

    if (!$role) {
        return response()->json(['message' => 'Employee role not defined.'], 404);
    }

    $employees = User::where('store_id', $store->id)
        ->where('role_id', $role->id)
        ->paginate($limit)->items();

    // if ($employees->isEmpty()) {
    //     return response()->json(['message' => 'No employees found.'], 200);
    // }

    return response()->json([
        'message' => 'Employees retrieved successfully.',
        'data' => $employees
    ], 200);
}

    public function deleteEmployee(User $user)
{

        if (!$user) {
            return response()->json(['message' => 'user not found.'], 404);
        }

         if ($user->role->role !== 'Store Employee') {
        return response()->json([
            'message' => 'Only users with the Store Employee role can be deleted.'
        ], 403);
    }

    $user->delete();

    return response()->json([
        'message' => 'Employee deleted successfully.'
    ], 200);

}


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
        $stores = Store::paginate($limit)->items(); 

        return response()->json([
            'message'=> 'success',
            'data'=>  $stores 
        ],200); 
    }

 

        
    /**
     * Display the specified resource.
     */
    public function show()
    {
        $store = Auth::user()->store;
       
         if (!$store) {
            return response()->json(['message' => 'Store not found.'], 404);
        }
       return response()->json([
            "message" => 'success', // Return success message in JSON format
            "data" => $store
        ], 200);
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreRequest $request, Store $store)
    {
        $authstore = Auth::user()->store;
        
        if(!$store){
            return response()->json(['message' => 'Store not found.'], 404);
        }

         if ($authstore->id !== $store->id) {
    return response()->json(['message' => 'Unauthorized.'], 403);
}

      $store->update($request->validated()); 

        return response()->json([
            'message' => 'Store updated successfully',
            'store' => $store
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Store $store)
    {
        // $authstore = Auth::user()->store;
        
            if (!$store) {
            return response()->json(['message' => 'store not found.'], 404);
        }

        // if ($authstore->id !== $store->id) {
        // return response()->json(['message' => 'Unauthorized.'], 403);
        //  }


        $store->is_active = false;

         $store->delete();

        return response()->json([ // Return a JSON response indicating success
            'message' => 'Store Deleted Successfully'
        ],200);
    }


    public function getStoreRatings(Store $store) {
    $averageRating = $store->ratings()->avg('rating');

    return response()->json([
         'message' => 'Success',
        'average_rating' => round($averageRating, 2)
    ], 200);
}
}
