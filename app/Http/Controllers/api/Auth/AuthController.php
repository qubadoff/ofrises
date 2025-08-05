<?php

namespace App\Http\Controllers\api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $customer = Customer::query()->where('email', $request->email)->first();

        if (!$customer || !Hash::check($request->password, $customer->password)) {
            return response()->json([
                'message' => 'Email or password is incorrect !',
            ], 401);
        }

        $token = $customer->createToken('customerLoginToken')->plainTextToken;

        return response()->json([
            'token' => $token,
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'surname' => $customer->surname,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'birthday' => $customer->birthday,
                'sex' => $customer->sex,
                'photo' => url('/') . '/storage/' . $customer->photo,
                'email_verified_at' => $customer->email_verified_at,
            ],
        ]);
    }
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|string|max:20',
            'birthday' => 'required|date',
            'sex' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:10000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('customer/photos', 'public');
        }

        $customer = Customer::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'phone' => $request->phone,
            'birthday' => $request->birthday,
            'sex' => intval($request->sex),
            'password' => Hash::make($request->password),
            'photo' => $photoPath,
        ]);

        $token = $customer->createToken('customerRegosterToken')->plainTextToken;

        $otp = rand(1000, 9999);
        $customer->otp_code = $otp;
        $customer->otp_expires_at = Carbon::now()->addMinutes(5);
        $customer->save();

        Mail::raw("OTP confirmation code : $otp", function ($message) use ($customer) {
            $message->to($customer->email)
                ->subject('Ofrises OTP code');
        });

        return response()->json([
            'token' => $token,
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'surname' => $customer->surname,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'birthday' => $customer->birthday,
                'sex' => $customer->sex,
                'photo' => url('/') . '/storage/' . $customer->photo,
                'email_verified_at' => $customer->email_verified_at,
            ],
        ]);
    }
    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:customers,email',
            'otp_code' => 'required|string'
        ]);

        $customer = Customer::query()->where('email', $request->email)->first();

        if (
            !$customer ||
            $customer->otp_code !== $request->otp_code ||
            Carbon::now()->greaterThan($customer->otp_expires_at)
        ) {
            return response()->json(['message' => 'Unknown or expired OTP code !'], 422);
        }

        $customer->otp_code = null;
        $customer->otp_expires_at = null;
        $customer->email_verified_at = now();
        $customer->save();

        return response()->json(['message' => 'Email confirmation success !']);
    }

    public function requestReset(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:customers,email'
        ]);

        $customer = Customer::query()->where('email', $request->email)->first();

        $otp = rand(1000, 9999);
        $expiresAt = Carbon::now()->addMinutes(5);

        $customer->otp_code = $otp;
        $customer->otp_expires_at = $expiresAt;
        $customer->save();

        Mail::raw("Password reset OTP code: $otp", function ($message) use ($customer) {
            $message->to($customer->email)->subject('Password reset OTP code');
        });

        return response()->json(['message' => 'The OTP code has been sent to your email !']);
    }

    public function verifyResetOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'otp_code' => 'required'
        ]);

        $customer = Customer::query()->where('email', $request->email)->first();

        if (
            !$customer ||
            $customer->otp_code !== $request->otp_code ||
            now()->greaterThan($customer->otp_expires_at)
        ) {
            return response()->json(['message' => 'OTP code undefined or expired !'], 422);
        }

        $customer->otp_code = null;
        $customer->otp_expires_at = null;
        $customer->save();

        return response()->json(['message' => 'OTP code has been verified !']);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:customers,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $customer = Customer::query()->where('email', $request->email)->first();

        $customer->password = Hash::make($request->password);
        $customer->save();

        return response()->json(['message' => 'The password has been reset !']);
    }
}
