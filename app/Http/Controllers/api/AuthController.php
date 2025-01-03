<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;  // Import Carbon untuk menangani waktu

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }

        // Menambahkan expiration ke token
        $token = $user->createToken('auth_token', ['*'])
            ->plainTextToken;

        // Menetapkan waktu kadaluarsa token
        $expiration = Carbon::now()->addMinutes(config('sanctum.expiration'));  // Gunakan waktu kadaluarsa yang ditentukan di config

        return response()->json([
            'message' => 'Login successful.',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => $expiration,  // Menyertakan waktu kadaluarsa
            'user' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }

    public function userInfo(Request $request)
    {
        return response()->json(['user' => $request->user()]);
    }
}
