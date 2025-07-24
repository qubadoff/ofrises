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
            'customer' => $customer,
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
            'sex' => 'required|integer',
            'password' => 'required|string|min:6|confirmed',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10000'
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
            'sex' => $request->sex,
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
            'customer' => $customer
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
}
