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
        try {
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
        } catch (\Exception $e) {
            return ResponseHandler::response(500, 'Auth:login', 'Login failed', $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try {
            $this->jwtService->invalidateToken($request->attributes->get('auth_token'));
            return ResponseHandler::response(200, 'Auth:logout', 'Logout successful', null);
        } catch (\Exception $e) {
            return ResponseHandler::response(500, 'Auth:logout', 'Logout failed', $e->getMessage());
        }
    }

    public function forgotPassword(Request $request)
    {
        return ResponseHandler::response(501, 'Auth:forgotPassword', 'Forgot password functionality not yet implemented', null);
    }

    public function resetPassword(Request $request)
    {
        return ResponseHandler::response(501, 'Auth:resetPassword', 'Password reset functionality not yet implemented', null);
    }
}
