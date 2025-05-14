<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest; 
use App\Http\Requests\OwnerRegisterRequest; 
use App\Models\User; 
use App\Models\Role;   
use App\Models\Store; 
use Illuminate\Support\Facades\Hash; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Storage;
class AuthController extends Controller
{


    public function register(OwnerRegisterRequest $request)
    {
        $user = $request->validated();

         if ($request->hasFile('legal')) {
            //$filePath = $request->file('legal')->storeAs('legal', $user['name_en']);
    
            $file = $request->file('legal');
            $filename = $user['name_en'] . '.' . $file->getClientOriginalExtension(); // Keeps the original extension
            $filePath = $file->storeAs('legal', $filename, 'public');   
        } else {
            return response()->json(['error' => 'File upload failed'], 400);
        }

        // Create store with the correct legal field
        $store = Store::create([
            'legal' => $filePath, // Store uploaded file path
            'product_category_id' => $user['product_category_id']
        ]);
        

        $user['store_id'] = $store->id;
        

        
$role = Role::where('role', ' Store Owner')->first();

if (!$role) {
    return response()->json(['error' => 'Role "Store Owner" not found in the database'], 404);
}

$user['role_id'] = $role->id;

        
        $user = User::create($user); // Create a new user with validated data

        return response()->json([
            'message' => 'User Created Successfully', // Success message
            'data' => $user, // Include the created user data in the response
        ]);
    }





//     public function register(OwnerRegisterRequest $request)
// {
//     // Store the uploaded file in storage/app/public/legal
//     $filePath = $request->file('legal')->store('legal', 'public');

//     // Create user and save file path
//     $user = User::create([
//         'name_ar' => $request->name_ar,
//         'name_en' => $request->name_en,
//         'phone' => $request->phone,
//         'email' => $request->email,
//         'password' => Hash::make($request->password),
//         'legal' => $filePath, // Save file path
//         'product_category_id' => $request->product_category_id,
//     ]);

//     return response()->json([
//         'message' => 'User Created Successfully',
//         'data' => $user,
//     ]);
// }



// public function register(OwnerRegisterRequest $request)
// {
//     try {
//         // Debugging: Log request input
//         Log::info('Register Request Data:', $request->all());

//         // Store the uploaded file
//         if ($request->hasFile('legal')) {
//             $filePath = $request->file('legal')->store('legal', 'public');
//         } else {
//             $filePath = null;
//         }

//         // Create user
//         $user = User::create([
//             'name_ar' => $request->name_ar,
//             'name_en' => $request->name_en,
//             'phone' => $request->phone,
//             'email' => $request->email,
//             'password' => Hash::make($request->password),
//             'legal' => $filePath,
//             'product_category_id' => $request->product_category_id,
//         ]);

//         return response()->json([
//             'message' => 'User Created Successfully',
//             'data' => $user,
//         ]);
//     } catch (\Exception $e) {
//         Log::error('Error in Register:', ['message' => $e->getMessage(), 'trace' => $e->getTrace()]);
//         return response()->json(['error' => 'Something went wrong.'], 500);
//     }
// }

    public function login(LoginRequest $request)
    {
        $cardinals = $request->validated(); // Retrieve validated login data

        $user = User::where('email', $cardinals['email'])->first(); // Find the user by email

        // Verify user existence and password
        if (!$user || !Hash::check($cardinals['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid Credentials' // Error message for invalid login
            ], 401); // Unauthorized response status
        }

        // Create an authentication token for the user
        $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;

        return response()->json([
            'message' => 'Login Success', // Success message
            'access_token' => $token, // Include the generated token
            'data' => $user, // Include the user data in the response
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete(); // Revoke the current user's token

        return response()->json([
            'message' => 'Logged out successfully!' // Success message for logout
        ], 200); // OK response status
    }
}
