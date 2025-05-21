<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'success'   =>  'false',
                'message'   =>  'validasi gagal',
                'errors'    =>  $validated->errors()
            ]);
        }

        $data = $validated->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return new UserResource(true, 'Akun Berhasil Dibuat', $user, $token);
    }

    public function login(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'email' => 'required|email',
            'password'  =>  'required|min:6'
        ]);

        if ($validated->fails()) {
            return response()->json([
                'success'   =>  'false',
                'message'   =>  'validasi gagal',
                'errors'    =>  $validated->errors()
            ],422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password))
        {
            return response()->json([
                'success'   =>  false,
                'message'   =>  'Email atau Password salah'
            ], 401);
        }

        $user->update(['status' => 'active']);
        $token = $user->createToken('auth_token')->plainTextToken;

        return new UserResource(true, 'Berhasil Login', $user, $token);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->update(['status' => 'inactive']);
        $request->user()->currentAccessToken()->delete();

        return new ApiResource(true, 'Berhasil Logout');
    }
}
