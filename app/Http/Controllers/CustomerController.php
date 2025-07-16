<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\CustomerLocationResource;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
        $customers = Customer::paginate($limit)->items();

        return response()->json([
            'message' => 'success',
            'data' =>  $customers
        ], 200);
    }



    public function show()
    {

        $customer = Auth::user();
        if (!$customer) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        //    $customer = Customer::with('locations')->get();
        $customer = Customer::with('locations')->find($customer->id);

        $customer = CustomerResource::make($customer);

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
        $customer = Customer::find($authUser->id);

        $customer->update($request->validated());

        return response()->json([
            'message' => 'success',
            'data' => new CustomerResource($customer)
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
