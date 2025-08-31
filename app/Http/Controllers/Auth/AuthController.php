<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends BaseController
{

    public function login(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'email'],
                'password' => ['required', 'string'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors(),
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid credentials.',
                ], Response::HTTP_UNAUTHORIZED);
            }

            $tokenResult = $user->createToken('auth_token');
            $token = $tokenResult->accessToken;
            $token->expires_at = now()->addHours(24);
            $token->save();

            return response()->json([
                'status' => 'success',
                'message' => 'User logged in successfully.',
                'data' => [
                    'token_type' => 'Bearer',
                    'access_token' => $tokenResult->plainTextToken,
                    'expires_at' => $tokenResult->accessToken->expires_at->format('Y-m-d H:i:s'),
                    'user' => $user,
                ]
            ], Response::HTTP_OK);

        } catch (Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Login failed.',
                'details' => $exception->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Successfully logged out.',
            ], Response::HTTP_OK);

        } catch (Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Logout failed.',
                'details' => $exception->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

}
