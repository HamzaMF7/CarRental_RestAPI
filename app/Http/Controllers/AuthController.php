<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRquest;
use App\Http\Requests\SignupRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
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
                'Password' => Hash::make($data['Password']),
            ]);

            // Generate access token
            $token = JWTAuth::fromUser($user);

            // Set token in HTTP-only cookie
            $cookie = cookie('access_token', $token, config('jwt.ttl'), null, null, false, true);

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
            $credentials = $request->validate();

            // // Check if the provided identity is an email or a username
            // $field = filter_var($credentials['identity'], FILTER_VALIDATE_EMAIL) ? 'Email' : 'Username';

            // // Authenticate user
            // if (!Auth::attempt([$field => $credentials['identity'], 'Password' => $credentials['Password']])) {
            //     return response()->json(['message' => 'Unauthorized'], 401);
            // }


            // if (!Auth::attempt($credentials)) {
            //     return response([
            //         'message' => 'Provided email or password is incorrect'
            //     ], 422);
            // }


            // // Retrieve authenticated user
            // $user = Auth::user();

            // // Generate access token
            // $token = JWTAuth::fromUser($user);

            // // Set token in HTTP-only cookie
            // $cookie = cookie('access_token', $token, config('jwt.ttl'), null, null, false, true);

            // // Return response with success message
            // return response()->json(['user' => $user, 'message' => 'loged successfully'])->withCookie($cookie);


            /** @var \App\Models\User $user */
            $user = Auth::user();
            $token = $user->createToken('main')->plainTextToken;
            return response(compact('user', 'token'));
        } catch (JWTException $e) {
            return response()->json(['message' => 'Failed to log in'], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            // Invalidate the token, user will need to log in again
            Auth::logout();

            return response()->json(['message' => 'Logged out successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to log out'], 500);
        }
    }

    public function refresh(Request $request)
    {
        try {
            // Get the old token from the request headers
            $oldToken = $request->header('Authorization');

            // Refresh access token
            $token = JWTAuth::refresh($oldToken);

            // Set token in HTTP-only cookie
            return $this->respondWithToken($token);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to refresh token'], 500);
        }
    }

    protected function respondWithToken($token)
    {
        // Set token in HTTP-only cookie
        $cookie = cookie('access_token', $token, config('jwt.ttl'), null, null, false, true);

        // Return response with success message
        return response()->json(['message' => 'Authentication successful'])->withCookie($cookie);
    }
}
