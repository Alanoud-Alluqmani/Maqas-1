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
use Twilio\Rest\Client;

//test4
class AuthController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;

        $this->middleware('auth:sanctum')->only(['logout']);
    }


    public function ownerRegister(OwnerRegisterRequest $request)
    {
        $user = $request->validated();


        if ($request->hasFile('legal')) {
            $file = $request->file('legal');
            $name = Str::slug($user['name_en'], '_'); // Remove spaces and special chars
            $filename = $name . '.' . $file->getClientOriginalExtension();
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



    public function customerRegister(CustomerRegisterRequest $request)
    {
        $customerData = $request->validated();
        $customerData['phone_verified_at'] = null;

        $customer = Customer::create($customerData);

        try {
            $twilio = new Client(
                config('services.twilio.sid'),
                config('services.twilio.token')
            );

            $twilio->verify->v2->services(config('services.twilio.service_sid'))
                ->verifications
                ->create($customer->phone, "sms");

            return response()->json([
                'message' => 'Customer created successfully. Verification code sent.',
                'data' => CustomerResource::make($customer),
                'otp' => [
                    'success' => true,
                    'message' => 'Verification code has been sent to ' . $customer->phone
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Customer created, but failed to send verification code.',
                'data' => CustomerResource::make($customer),
                'otp' => [
                    'success' => false,
                    'error' => 'Error while sending verification code',
                    'details' => $e->getMessage()
                ]
            ], 201);
        }
    }


    public function sendOTP(string $receiverNumber)
    {
        if (!$receiverNumber) {
            return response()->json(['error' => 'Phone number is required'], 422);
        }

        try {
            $twilio = new Client(
                config('services.twilio.sid'),
                config('services.twilio.token')
            );

            $twilio->verify->v2->services(config('services.twilio.service_sid'))
                ->verifications
                ->create($receiverNumber, "sms");

            return response()->json([
                'success' => true,
                'message' => 'Verification code has been sent to ' . $receiverNumber
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error while sending verification code',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function verifyOTP(Request $request)
    {
        $receiverNumber = $request->input('phone');
        $code = $request->input('code');

        if (!$receiverNumber || !$code) {
            return response()->json(['error' => 'Phone number and code are required'], 422);
        }

        try {
            $twilio = new Client(
                config('services.twilio.sid'),
                config('services.twilio.token')
            );

            $verification = $twilio->verify->v2->services(config('services.twilio.service_sid'))
                ->verificationChecks
                ->create([
                    'to' => $receiverNumber,
                    'code' => $code,
                ]);

            if ($verification->status === 'approved') {
                $user = Customer::where('phone', $receiverNumber)->first();

                if (!$user) {
                    return response()->json(['error' => 'User not found'], 404);
                }

                $user->update([
                    'phone_verified_at' => now(),
                ]);

                $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;

                return response()->json([
                    'success' => true,
                    'message' => 'Verification successful. Logged in.',
                    'access_token' => $token,
                    'data' => $user,
                ]);
            } else {
                return response()->json(['error' => 'Invalid verification code'], 401);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Verification error',
                'details' => $e->getMessage()
            ], 500);
        }
    }


    public function customerLogin(Request $request)
    {
        $credentials = $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = Customer::where('phone', $credentials['phone'])->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if (!$user->phone_verified_at) {
            return response()->json(['message' => 'Phone number is not verified'], 403);
        }

        if (!Auth::guard('customer')->attempt($credentials)) {
            return response()->json(['message' => 'Invalid phone number or password'], 401);
        }

        Cache::put('otp_login_requested_' . $user->phone, now(), now()->addMinutes(5));

        $otpResponse = $this->sendOTP($user->phone);

        return $otpResponse;
    }



    public function verifyLoginOTP(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'code' => 'required|string',
        ]);

        $user = Customer::where('phone', $request->phone)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $requestedAt = Cache::get('otp_login_requested_' . $request->phone);

        if (!$requestedAt || now()->diffInMinutes($requestedAt) > 5) {
            return response()->json(['error' => 'OTP expired or not requested'], 403);
        }

        try {
            $twilio = new Client(
                config('services.twilio.sid'),
                config('services.twilio.token')
            );

            $verification = $twilio->verify->v2->services(config('services.twilio.service_sid'))
                ->verificationChecks
                ->create([
                    'to' => $request->phone,
                    'code' => $request->code,
                ]);

            if ($verification->status === 'approved') {

                Cache::forget('otp_login_requested_' . $request->phone);

                $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;

                return response()->json([
                    'message' => 'Verification successful. Logged in.',
                    'access_token' => $token,
                    'data' => $user,
                ]);
            } else {
                return response()->json(['error' => 'Invalid verification code'], 401);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Verification error',
                'details' => $e->getMessage()
            ], 500);
        }
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
