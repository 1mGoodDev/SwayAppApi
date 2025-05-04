<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException as ValidationValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return new UserResource(true, 'Akun Berhasil Dibuat', $user, $token);
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password))
        {
            throw ValidationValidationException::withMessages(['message' => 'Email or Password incorrect']);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        $user->update(['status' => 'active']);

        return new UserResource(true, 'Berhasil Login', $user, $token);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->update(['status' => 'inactive']);
        $request->user()->currentAccessToken()->delete();

        return response()->json(['Berhasil Logout']);
    }
}
