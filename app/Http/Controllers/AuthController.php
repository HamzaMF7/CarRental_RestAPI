<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

use App\Http\Requests\LoginRquest;
use App\Http\Requests\SignupRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Lcobucci\JWT\Token\InvalidTokenStructure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth', except: ['signup', 'login', 'refresh']),
        ];
    }
    public function signup(SignupRequest $request)
    {
        try {
            // Validate incoming request
            $data = $request->validated();

            // Create a new user
            $user = User::create([
                'FirstName' => $data['FirstName'],
                'LastName' => $data['LastName'],
                'Email' => $data['Email'],
                'Username' => $data['Username'],
                'Password' => bcrypt($data['Password']),
            ]);

            // Generate access token
            $token = JWTAuth::fromUser($user);

            // Set token in HTTP-only cookie
            $cookie = cookie('access_token', $token, config('jwt.ttl'), secure: true, httpOnly: true);

            // Return response with success message
            return response()->json(['user' => $user, 'message' => 'registered successfully'])->withCookie($cookie);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create user.'], 500);
        }
    }

    public function login(LoginRquest $request)
    {
        try {
            // Validate incoming request
            $credentials = $request->validated();

            // Retrieve the user by email
            $user = User::where('Email', $credentials['Email'])->first();

            // Check if the user exists and the password matches
            if (!$user || !Hash::check($credentials['Password'], $user->Password)) {
                return response()->json(['message' => 'Provided email or password is incorrect'], 422);
            }

            // Generate access token
            $token = JWTAuth::fromUser($user);

            // Set token in HTTP-only cookie
            $cookie = cookie('access_token', $token, config('jwt.ttl'), secure: true, httpOnly: true);

            // Return response with success message
            return response()->json(['user' => $user, 'message' => 'Logged in successfully'])->withCookie($cookie);
        } catch (JWTException $e) {
            return response()->json(['message' => 'Failed to log in'], 500);
        }
    }


    public function logout(Request $request)
    {
        try {
            // Invalidate the token, user will need to log in again
            $logout = auth()->logout();

            return response()->json(['message' => 'Logged out successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to log out'], 500);
        }
    }

    public function refresh(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return response()->json(["message" => 'Token still valid']);
        } catch (\Exception $e) {
            if ($e instanceof TokenExpiredException) {
                $newToken = JWTAuth::parseToken()->refresh();
                // Set token in HTTP-only cookie
                $cookie = cookie('refresh_token', $newToken, config('jwt.refresh_ttl'), secure: true, httpOnly: true);
                return response()->json(['message' => 'Token refreshed successfully'], 200)->withCookie($cookie);
            } else if ($e instanceof TokenInvalidException) {
                return response()->json(['message' => 'Invalid Token'], 401);
            } else {
                return response()->json(['message' => 'Token not found'], 401);
            }
        }
    }

    // protected function respondWithToken($token)
    // {
    //     // Set token in HTTP-only cookie
    //     $cookie = cookie('access_token', $token, config('refresh_ttl'), null, null, false, true);

    //     // Return response with success message
    //     return response()->json(['message' => 'Authentication successful'])->withCookie($cookie);
    // }
}
