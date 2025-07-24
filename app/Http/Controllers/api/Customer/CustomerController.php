<?php

namespace App\Http\Controllers\api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function __construct(){}

    public function me(Request $request): JsonResponse
    {
        $customer = $request->user();

        return response()->json([
            'id' => $customer->id,
            'name' => $customer->name,
            'surname' => $customer->surname,
            'email' => $customer->email,
            'phone' => $customer->phone,
            'birthday' => $customer->birthday,
            'sex' => $customer->sex,
            'photo' => url('/storage') . $customer->photo,
            'email_verified_at' => $customer->email_verified_at,
        ]);
    }

    public function updatePhoto(Request $request): JsonResponse
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $user = $request->user();

        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        $newPath = $request->file('photo')->store('photos', 'public');

        $user->photo = $newPath;
        $user->save();

        return response()->json([
            'photo' => url('/') . '/storage/' . $newPath,
        ]);
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'surname' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255|unique:customers,email,' . $request->user()->id,
            'phone' => 'sometimes|string|max:20',
            'birthday' => 'sometimes|date',
            'sex' => 'sometimes|integer|in:1,3',
        ]);

        $customer = $request->user();

        $customer->update($request->only([
            'name',
            'surname',
            'email',
            'phone',
            'birthday',
            'sex',
        ]));

        return response()->json([
            'id' => $customer->id,
            'name' => $customer->name,
            'surname' => $customer->surname,
            'email' => $customer->email,
            'phone' => $customer->phone,
            'birthday' => $customer->birthday,
            'sex' => $customer->sex,
            'photo' => url('/storage') . $customer->photo,
            'email_verified_at' => $customer->email_verified_at,
        ]);
    }


    public function logout(Request $request): void
    {
        $request->user()->tokens()->delete();
    }
}
