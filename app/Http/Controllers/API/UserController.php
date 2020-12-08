<?php

namespace App\Http\Controllers\API;

use App\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Carbon\Carbon;
use JWTAuth;
use Auth;

class UserController extends Controller
{

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    "message" => "invalid credentials",
                    "data" => null
                ], 400);
            }
        } catch (JWTException $e) {
            return response()->json([
                "message" => "could not create token",
                "data" => null
            ], 500);
        }

        return response()->json([
            "message" => "User Success to Login",
            "data" => compact('token')
        ], 200);
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
                "message" => $validator->errors(),
                "data" => null
            ], 400);
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
            "message" => "User Success to Create",
            "data" => $user
        ], 200);
    }

    public function getAuthenticatedUser()
    {
        $user = Auth::user();

        return response()->json([
            "message" => "Data user success to get",
            "data" => $user
        ], 200);
    }
}
