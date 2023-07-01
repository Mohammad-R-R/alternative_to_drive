<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function signIn(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('AuthToken')->accessToken;
            // Authentication successful
            // $user = User::filter_input('email');

            return response()->json(['message' => 'Sign in successful', 'user'=>$user->email,'token' => $token]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function signUp(Request $request)
    {
        $data = $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'number' => 'required|numeric',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'dressName' => 'required',
        ]);

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        return response()->json(['message' => 'Sign up successful', 'user'=>$user]);
    }
}
