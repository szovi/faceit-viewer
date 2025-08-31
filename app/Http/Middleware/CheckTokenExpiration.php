<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenExpiration
{

    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['status' => 'error', 'message' => 'Token does not exists.'], 401);
        }

        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken) {
            return response()->json(['status'=>'error','message'=>'Invalid token'], 401);
        }

        if ($accessToken->expires_at && now()->gt($accessToken->expires_at)) {
            $accessToken->delete(); // töröljük a lejárt tokent
            return response()->json(['status'=>'error','message'=>'Token expired'], 401);
        }

        return $next($request);
    }
}
