<?php

namespace App\Http\Controllers;

use App\Http\Middleware\VerifyJWT;
use App\Http\Requests\AdminLoginRequest;
use App\Http\Requests\AdminRegisterRequest;
use App\Models\Admin;
use App\Models\RefreshToken;
use Carbon\Carbon;
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
            // new Middleware('auth:admin', except: ['login', 'refresh', 'register', 'protectedResource']),
            // new Middleware(VerifyJWT::class, except: ['login', 'refresh', 'register']),
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
            $accessTokenClaims = [
                'admin_id' => $admin->id,
                'firstName' => $admin->FirstName,
                'lastName' => $admin->LastName,
                'role_id' => $admin->RoleID
            ];

            $refreshTokenClaims = [
                'firstName' => $admin->FirstName,
                'lastName' => $admin->LastName,
            ];

            // // Generate access and refresh token 
            $accesstoken = JWTAuth::customClaims($accessTokenClaims)->fromUser($admin);
            $refreshToken = JWTAuth::customClaims($refreshTokenClaims)->fromUser($admin);

            RefreshToken::create([
                'admin_id' => $admin->id,
                'token' => $refreshToken,
                'expires_at' => Carbon::now()->addWeeks(2), // Example expiration date
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);


            // Set token in HTTP-only cookie
            $cookie = cookie('refresh_token', $refreshToken, config('jwt.refresh_ttl'), httpOnly: true);

            // Return response with success message
            return response()->json(['admin' => $admin, 'access_token' => $accesstoken, 'message' => 'Logged in successfully'])->withCookie($cookie);
        } catch (JWTException $e) {
            return response()->json(['message' => 'Failed to log in'], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            // // Pass true to force the token to be blacklisted "forever"
            $logout = auth('admin')->logout();

            return response()->json(['message' => 'Logged out successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to log out'], 500);
        }
    }

    public function refresh(Request $request)
    {

        //extract the token from cookie 
        $token = $request->cookie('refresh_token');

        if (!$token) {
            return response()->json(['message' => "Token not found"], 401);
        }
        // verify the token in database
        $refreshToken = RefreshToken::where('token', $token)->first();

        if (!$refreshToken) {
            return  response()->json(['message' => 'Invalid token'], 401);
        } else if (Carbon::parse($refreshToken->expires_at)->isPast()) {
            return  response()->json(['message' => 'Token expired'], 403);
        } else {

            $newAccessToken = JWTAuth::refresh();
            $newRefreshToken = JWTAuth::refresh();

            // Update the refresh token
            $refreshToken->update([
                'token' => $newRefreshToken,
                'expires_at' => Carbon::now()->addWeeks(2),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // override the old token in HTTP-only cookie
            $cookie = cookie('refresh_token', $newRefreshToken, config('jwt.refresh_ttl'), httpOnly: true);


            return response()->json(["access_token" => $newAccessToken])->withCookie($cookie);
        }
    }

    public function protectedResource()
    {
        $payload = auth('admin')->payload();
        dd($payload);
        return response()->json(["message" => "accessed"]);
    }
}
