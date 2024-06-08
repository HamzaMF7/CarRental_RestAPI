<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class VerifyJWT
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Check if the token is present
            if (!$admin = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['message' => 'Your not authorized'], 401);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['message' => 'Token expired'], 403);
        } catch (TokenInvalidException $e) {
            return response()->json(['message' => 'Token invalid'], 401);
        } catch (Exception $e) {
            return response()->json(['message' => 'Token is required'], 401);
        }
        return $next($request);
    }
}
