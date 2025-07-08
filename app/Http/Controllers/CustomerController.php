<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateUserRequest;

class CustomerController extends Controller
{
      public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
        $customers = Customer::paginate($limit)->items(); 

        return response()->json([
            'message'=> 'success',
            'data'=>  $customers 
        ],200); 
    }


    
   public function show()
    {
        /** @var \App\Models\User $user */
        $$customer = Auth::user();
        if (!$$customer) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        return response()->json([
            'message' => 'success',
            'data' => $customer
        ], 200);
    }


    public function update(UpdateUserRequest $request, Customer $customer)
    {
        $authUser = Auth::user();
        if (!$authUser) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        $customer->update($request->validated());

        return response()->json([
            'message' => 'success',
            'data' =>  $customer
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
