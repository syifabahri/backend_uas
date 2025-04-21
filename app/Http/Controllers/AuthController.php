<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    public function login(LoginRequest $loginRequest)
    {
        $data = $loginRequest->validated();

        if (! Auth::attempt($data)) {
            throw ValidationException::withMessages([
                'username' => __('auth.failed'),
            ]);
        }

        return Auth::user()->createToken('mobile-token')->plainTextToken;
    }
}
