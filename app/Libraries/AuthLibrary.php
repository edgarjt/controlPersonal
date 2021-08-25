<?php

namespace App\Libraries;

use App\User;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
class AuthLibrary
{
    public function loginLibrary($request)
    {
        try {
            $credentials = $request->only("email", "password");
            $validator = Validator::make($credentials, [
                'email' => 'required',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'response' => false,
                    'errors' => $validator->errors(),
                    'code' => 422
                ], 422);
            }

            if (!$token = JWTAuth::attempt([
                'email' => $request->email,
                'password' => $request->password
            ])
            ) {
                return response()->json(['status' => false,'message' => 'Unauthorized', 'code' => 401], 401);
            }

            $data = JWTAuth::user();
            $userData = User::where('id', $data->id)->with('role')->first();

            return response()->json([
                'status' => true,
                'messaje' => 'Bienvenido ' . $userData->name . ' ' . $userData->surname,
                'userData' => $userData,
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 1440

            ]);
        }catch (\Throwable $e) {
            logger('Auth exception: ' . $e);
            return response()->json(['status' => false, 'message' => 'Internal server error', 'code' => 500], 500);
        }
    }

    public function logoutLibrary()
    {
        JWTAuth::invalidate();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);

    }

    public function refreshLibrary()
    {
        return response()->json([
            'access_token' => JWTAuth::refresh(),
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 1440
        ]);
    }
}
