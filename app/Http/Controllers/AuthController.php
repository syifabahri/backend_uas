<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Proses login user
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        $user = User::whereRaw('BINARY username = ?', [$credentials['username']])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'username' => 'Username atau password salah.',
            ]);
        }

        return response()->json([
            'token' => $user->createToken('mobile-token')->plainTextToken,
            'user' => new UserResource($user),
        ]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }

        return response()->json([
            'message' => 'Logout berhasil.',
        ]);
    }

    /**
     * Register user baru
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6',
            'email'    => 'required|email|unique:users,email',
        ]);

        $user = User::create([
            'user_id'         => (string) Str::ulid(), // karena kamu pakai ULID
            'name'            => $data['name'],
            'username'        => strtolower($data['username']),
            'email'           => $data['email'],
            'password'        => Hash::make($data['password']),
            'membership_date' => now()->toDateString(), // default saat register
        ]);

        return response()->json([
            'message' => 'User berhasil terdaftar',
            'user'    => new UserResource($user),
        ], 201);
    }
}
