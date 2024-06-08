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
use Illuminate\Support\Facades\Auth;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Claims\JwtId;

class AdminAuthController extends Controller
{
    public function register(AdminRegisterRequest $request)
    {
        try {
            // Validate incoming data
            $data = $request->validated();

            // dd($data);

            // create the admin 
            $admin = Admin::create([
                'firstName' => $data['firstName'],
                'lastName' => $data['lastName'],
                'email' => $data['email'],
                'username' => $data['username'],
                'password' => bcrypt($data['password']),
                'roleID' => $data['roleID'],
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

            // dd($credentials);
            // $admin = JWTAuth::attempt($credentials);
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            // get the authenticated admin
            $admin = JWTAuth::user();


            // Customize the token payload with the admin's ID
            $accessTokenClaims = [
                'admin_id' => $admin->id,
                'firstName' => $admin->firstName,
                'lastName' => $admin->lastName,
                'role_id' => $admin->roleID
            ];

            $refreshTokenClaims = [
                'firstName' => $admin->firstName,
                'lastName' => $admin->lastName,
            ];

            // // Generate access and refresh token 
            $accessToken = JWTAuth::customClaims($accessTokenClaims)->fromUser($admin);
            $refreshToken = JWTAuth::customClaims($refreshTokenClaims)->fromUser($admin);

            RefreshToken::create([
                'admin_id' => $admin->id,
                'token' => $refreshToken,
                'expires_at' => Carbon::now()->addWeeks(2), // Example expiration date
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);


            // Set token in HTTP-only cookie
            $cookie = cookie('refresh_token', $refreshToken, config('jwt.refresh_ttl'), '/', null, false, true, false, 'None');
            // Return response with success message
            return response()->json(['token' => $accessToken, 'message' => 'Logged in successfully'])->withCookie($cookie);
        } catch (JWTException $e) {
            return response()->json(['message' => 'Failed to log in'], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            // Get the authenticated admin
            $admin = JWTAuth::parseToken()->authenticate();

            // Revoke all refresh tokens associated with the admin
            DB::table('refresh_tokens')
                ->where('admin_id', $admin->id)
                ->delete();

            // Invalidate the current access token
            JWTAuth::invalidate($request->bearerToken());

            return response()->json(['message' => 'Logged out successfully']);
        } catch (TokenInvalidException $e) {
            // Handle the case when the token is invalid
            return response()->json(['message' => 'Invalid token'], 401);
        } catch (JWTException $e) {
            // Handle other JWT-related exceptions
            return response()->json(['message' => 'Failed to log out'], 500);
        } catch (\Exception $e) {
            // Handle any other exceptions
            return response()->json(['message' => 'Internal server error'], 500);
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

            JWTAuth::setToken($token);
            $newAccessToken = JWTAuth::refresh();
            $newRefreshToken = JWTAuth::refresh();

            // return  response()->json(['new access' => $newAccessToken], 200);

            // Update the refresh token
            $refreshToken->update([
                'token' => $newRefreshToken,
                'expires_at' => Carbon::now()->addWeeks(2),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // override the old token in HTTP-only cookie
            $cookie = cookie('refresh_token', $newRefreshToken, config('jwt.refresh_ttl'), '/', null, false, true, false, 'None');

            return response()->json(["token" => $newAccessToken])->withCookie($cookie);
        }
    }

    public function protectedResource()
    {
        // $payload = auth('admin')->payload();   
        // dd($payload);
        return response()->json(["users" => "No users at the moment"]);
    }
}
