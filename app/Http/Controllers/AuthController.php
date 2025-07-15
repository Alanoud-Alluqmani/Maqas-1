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
use App\Http\Requests\CustomerRegisterRequest;
use App\Http\Requests\CoAdminRegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Resources\CustomerResource;
use App\Models\User;
use App\Models\Role;
use App\Models\Store;
use App\Models\Customer;
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

        $this->middleware('auth:sanctum')->only(['logout']);
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


    public function customerRegister(CustomerRegisterRequest $request){

        $customer = $request->validated();

        $customer = Customer::create($customer);

        $customer = CustomerResource::make($customer);
        return response()->json([
            'message' => 'Customer Created Successfully',
            'data' => $customer
        ], 201);
    }

    //دخول مؤقت (بالباسوورد)
     public function customerLogin(Request $request)
    {
       $cardinals = $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = Customer::where('phone', $cardinals['phone'])->first();

        // if (!Auth::attempt($cardinals)) {
        //     return response()->json(['message' => 'Invalid phone number or password'], 401);
        // }

          if (!Auth::guard('customer')->attempt($cardinals)) {
         return response()->json(['message' => 'Invalid phone number or password'], 401);
        };

        $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;

        return response()->json([
            'message' => 'Login Success',
            'access_token' => $token,
            'data' => $user,
        ], 200);
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
}
