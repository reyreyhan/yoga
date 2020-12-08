<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Carbon\Carbon;
use JWTAuth;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = Auth::attempt($credentials)) {
                return response()->json([
                    "status" => 400,
                    "message" => "invalid credentials",
                    "data" => null
                ]);
            }
        } catch (JWTException $e) {
            return response()->json([
                "status" => 500,
                "message" => "could not create token",
                "data" => null
            ]);
        }

        return response()->json([
            "status" => 200,
            "message" => "User Success to Login",
            "data" => compact('token')
        ]);
    }

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:user',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => 400,
                "message" => $validator->errors(),
                "data" => null
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'created' => Carbon::now()->toDateTimeString(),
            'updated' => Carbon::now()->toDateTimeString()
        ]);

        $user->token = JWTAuth::fromUser($user);

        return response()->json([
            "status" => 200,
            "message" => "User Success to Create",
            "data" => $user
        ]);
    }

    public function getAuthenticatedUser()
    {
        $user = Auth::id();

        return response()->json([
            "status" => 200,
            "message" => "Data user success to get",
            "data" => $user
        ]);
    }
}
