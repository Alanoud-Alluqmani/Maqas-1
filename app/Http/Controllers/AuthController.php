<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\OwnerRegisterRequest;
use App\Http\Requests\EmployeeRegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\CoAdminRegisterRequest;
use App\Models\PartneringOrder;
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
use App\Models\Customer;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use App\Services\Infobip2FAService;
use App\Services\SmsService;
use Illuminate\Container\Attributes\Cache;
use Illuminate\Support\Facades\Cache as FacadesCache;
use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use Vonage\SMS\Message\SMS;








namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\OwnerRegisterRequest;
use App\Http\Requests\CoAdminRegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use App\Models\Role;
use App\Models\Store;
use App\Services\SmsService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;

        // Middleware setup
        $this->middleware('auth:sanctum')->only(['logout']);
        $this->middleware('guest')->only(['forgotPassword', 'resetPassword']);
        $this->middleware(['auth:sanctum', 'role:Super Admin'])->only(['coAdminRegister', 'deleteCoAdmin']);
    }

    public function sendPin(Request $request)
    {
        $request->validate([
            'phone' => 'required|string'
        ]);

        $phone = $request->input('phone');
        $appId = config('services.infobip.app_id');

        $response = $this->smsService->sendPinCode($appId, $phone);

        if (isset($response['pinId'])) {
            Cache::put("pin_{$phone}", $response['pinId'], now()->addMinutes(5));
            return response()->json(['message' => 'PIN sent successfully.']);
        }

        return response()->json([
            'message' => 'Failed to send PIN.',
            'error' => $response,
        ], 400);
    }

    public function verifyPin(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'pin' => 'required|string',
        ]);

        $phone = $request->input('phone');
        $pin = $request->input('pin');

        $pinId = Cache::get("pin_{$phone}");

        if (!$pinId) {
            return response()->json(['message' => 'PIN expired or not found.'], 404);
        }

        $response = $this->smsService->verifyPinCode($pinId, $pin);

        if (isset($response['verified']) && $response['verified'] === true) {
            return response()->json(['message' => 'Phone number verified successfully.']);
        }

        return response()->json([
            'message' => 'Invalid PIN.',
            'error' => $response,
        ], 401);
    }

    public function ownerRegister(OwnerRegisterRequest $request)
    {
        $user = $request->validated();

        if ($request->hasFile('legal')) {
            $file = $request->file('legal');
            $filename = $user['name_en'] . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('legal', $filename, 'public');
        } else {
            return response()->json(['message' => 'File upload failed'], 400);
        }

        $store = Store::create([
            'legal' => $filePath,
            'product_category_id' => $user['product_category_id']
        ]);

        $user['store_id'] = $store->id;

        $role = Role::where('role', 'Store Owner')->first();
        if (!$role) {
            return response()->json(['message' => 'Role "Store Owner" not found in the database'], 404);
        }

        $user['role_id'] = $role->id;
        $user = User::create($user);
        $store->partnering_order()->create();

        return response()->json([
            'message' => 'User Created Successfully',
            'data' => $user
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $cardinals = $request->validated();

        $user = User::where('email', $cardinals['email'])->first();

        if (!Auth::attempt($cardinals)) {
            return response()->json(['message' => 'Invalid email or password'], 401);
        }

        $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;

        return response()->json([
            'message' => 'Login Success',
            'access_token' => $token,
            'data' => $user,
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully!'], 200);
    }

    public function verifyEmail($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if ($user->email_verified_at) {
            return response()->json(['message' => 'Email is already verified'], 200);
        }

        $user->email_verified_at = now();
        $user->save();

        return response()->json(['message' => 'Email verified successfully!'], 200);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::broker('users')->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => __($status)], 200)
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

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]], 400);
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
