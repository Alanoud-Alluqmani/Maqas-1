<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest; 
use App\Http\Requests\OwnerRegisterRequest; 
use App\Http\Requests\EmployeeRegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User; 
use App\Models\Role;   
use App\Models\Store; 
use Illuminate\Support\Facades\Hash; 
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class AuthController extends Controller
{

       public function __construct()
{
    $this->middleware('auth:sanctum')->only(['logout']);
    $this->middleware('guest')->only(['forgotPassword', 'resetPassword']);
} 


    public function ownerRegister(OwnerRegisterRequest $request)
    {
        $user = $request->validated();

         if ($request->hasFile('legal')) {
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
        
        event(new Registered($user));
        return response()->json([
            'message' => 'User Created Successfully', // Success message
            'data' => $user, // Include the created user data in the response
        ]);
    }

     public function emailVerify($id ,$hash, Request $request) {
        // Find user by ID
        $user = User::find($id);
    
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }
    
        // Verify if the hash is correct
        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json(['message' => 'Invalid verification link.'], 403);
        }
    
        // Mark email as verified
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }
    
        return response()->json(['message' => 'Email verified successfully!']);
    }


     public function resendEmailVerification(Request $request) {
        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }
    
        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.'], 400);
        }
    
        $user->sendEmailVerificationNotification();
        return response()->json(['message' => 'Verification link resent!']);
    }

    
    public function employeeRegister(EmployeeRegisterRequest $request, $id)
    {

        if (!$request->hasValidSignature()) {
        abort(403, 'Invalid or expired link.');}

        $user = $request->validated();

        // $store=Store:: where('id', $id)->first();
        // if (!$store) {
        //     return response()->json(['error' => 'Store not found in the database'], 404);
        // }
        $user['store_id'] = $id;
        $role = Role::where('role', 'Store Employee')->first();

        if (!$role) {
            return response()->json(['error' => 'Role "Store Employee" not found in the database'], 404);
        }

        $user['role_id'] = $role->id;

        
        $user = User::create($user); // Create a new user with validated data

        return response()->json([
            'message' => 'User Created Successfully', // Success message
            'data' => $user, // Include the created user data in the response
         ]);
    }

     public function generateLink($store_id)
    {

        $url = URL::signedRoute('employeeRegister', ['id' => $store_id]);
        return response()->json(['registration_link' => $url]);

    }



    public function login(LoginRequest $request)
    {
        $cardinals = $request->validated(); // Retrieve validated login data

        $user = User::where('email', $cardinals['email'])->first(); // Find the user by email


        if (!Auth::attempt($cardinals)){
            return response()->json([
                'message'=> 'Invalid email or password'
            ], 401);
        }

        if(is_null($user->email_verified_at)){
            return response()->json([
                'message' => 'This email is not verified yet' // Error message for invalid login
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

    public function verifyEmail($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if ($user->email_verified_at) {
            return response()->json(['message' => 'Email is already verified']);
        }

        $user->email_verified_at = now();
        $user->save();

        return response()->json(['message' => 'Email verified successfully!']);
    }


    public function forgotPassword(Request $request){
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::broker('users')->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => __($status)])
            : response()->json(['message' => __($status)], 400);
    }


    public function resetPassword(ResetPasswordRequest $request)
    {
        $request->validated();

        $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function (User $user, string $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PasswordReset
        ? redirect()->route('login')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);
    }

}


