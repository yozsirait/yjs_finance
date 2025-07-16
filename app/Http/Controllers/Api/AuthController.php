<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|string|min:6|confirmed',
        ]);
        $user = User::create([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>bcrypt($data['password']),
        ]);
        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json(['user'=>$user, 'token'=>$token]);
    }

    public function login(Request $request)
    {
        $request->validate(['email'=>'required|email', 'password'=>'required']);
        $user = User::where('email', $request->email)->first();

        if(!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email'=>['Email atau password salah.']
            ]);
        }

        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json(['user'=>$user, 'token'=>$token]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message'=>'Logout berhasil']);
    }
}
