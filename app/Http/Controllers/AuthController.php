<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\JwtService;
use App\Services\UserService;
use App\Shared\ResponseHandler;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private readonly UserService $userService, private readonly JwtService $jwtService)
    {
        //
    }

    public function login(Request $request)
    {
        $user = $this->userService->validateLoginData($request->all());

        if (!$user) {
            return ResponseHandler::response(401, 'Auth:login', 'Invalid Credentials', null);
        }

        $jwt = $this->jwtService->generateToken($user);

        return ResponseHandler::response(200, 'Auth:login', null, null, [
            'access_token' => $jwt,
            'expires_in'   => CarbonInterval::hours(config('app.user_auth.jwt_validity_time'))->totalSeconds,
            'token_type'   => 'Bearer',
        ]);
    }

    public function logout(Request $request)
    {
        $this->jwtService->invalidateToken($request->bearerToken());
        return response()->json([ 'message' => 'Logout successful' ], 200);
    }

    public function forgotPassword(Request $request)
    {
        return response()->json([ 'message' => 'Forgot password functionality not yet implemented' ], 501);
    }

    public function resetPassword(Request $request)
    {
        return response()->json([ 'message' => 'Password reset functionality not yet implemented' ], 501);
    }
}
