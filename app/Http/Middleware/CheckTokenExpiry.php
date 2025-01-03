<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CheckTokenExpiry
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if ($token) {
            $payload = \Laravel\Sanctum\PersonalAccessToken::findToken($token);

            // Memeriksa apakah token sudah kadaluarsa
            if ($payload && Carbon::parse($payload->created_at)->addMinutes(config('sanctum.expiration'))->isPast()) {
                return response()->json(['message' => 'Token expired.'], 401);
            }
        }

        return $next($request);
    }
}
