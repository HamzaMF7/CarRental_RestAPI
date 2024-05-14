<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminLoginRequest;
use App\Http\Requests\AdminRegisterRequest;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AdminAuthController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth:admin', except: ['login', 'refresh', 'register']),
        ];
    }

    public function register(AdminRegisterRequest $request)
    {
        try {
            // Validate incoming data
            $data = $request->validated();

            // create the admin 
            $admin = Admin::create([
                'FirstName' => $data['FirstName'],
                'LastName' => $data['LastName'],
                'Email' => $data['Email'],
                'Username' => $data['Username'],
                'Password' => Hash::make($data['Password']),
                'RoleID' => $data['RoleID'],
            ]);

            // Return response with success message
            return response()->json(['admin' => $admin, 'message' => 'registred successfully']);
        } catch (\Exception $e) {
            // Return error response
            return response()->json(['message' => 'Failed to register'], 500);
        }
    }

    public function login(AdminLoginRequest $request)
    {
        try {
            // Validate incoming request
            $credentials = $request->validated();

            //Retrieve the admin by email
            $admin = Admin::where('Email', $credentials['Email'])->first();


            // Check if the admin exists and the password matches
            if (!$admin || !Hash::check($credentials['Password'], $admin->Password)) {
                return response()->json(['message' => 'Provided email or password is incorrect'], 422);
            }

            // Customize the token payload with the admin's ID
            $customPayload = [
                'admin_id' => $admin->id,
                'admin_firstName' => $admin->FirstName,
                'admin_lastName' => $admin->LastName,
                'role_id' => $admin->RoleID
            ];

            // // Generate access token
            $token = JWTAuth::customClaims($customPayload)->fromUser($admin);

            // Set token in HTTP-only cookie
            $cookie = cookie('access_token', $token, config('jwt.ttl'), secure: true, httpOnly: true);

            // Return response with success message
            return response()->json(['admin' => $admin, 'message' => 'Logged in successfully'])->withCookie($cookie);
        } catch (JWTException $e) {
            return response()->json(['message' => 'Failed to log in'], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            // // Pass true to force the token to be blacklisted "forever"
            $logout = auth('admin')->logout(true);

            return response()->json(['message' => 'Logged out successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to log out'], 500);
        }
    }

    public function refresh(Request $request)
    {

        try {
            // Attempt to parse and authenticate the token
            $admin = JWTAuth::parseToken()->authenticate();
            return response()->json(['message' => 'Token still valid']);
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

    public function protectedResource()
    {
        $payload = auth('admin')->payload();
        dd($payload);
        return response()->json(["message" => "accessed"]);
    }
}
