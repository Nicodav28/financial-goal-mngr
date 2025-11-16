<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Resources\ApiResponseResource;
use App\Services\JwtService;
use App\Shared\ResponseHandler;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateAuthToken
{
    public function __construct(private readonly JwtService $jwtService){}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response | ApiResponseResource
    {
        try {
            $token = $this->extractToken($request);

            if (!$token) {
                return $this->unauthorizedResponse('Bearer token is required');
            }

            $authToken = $this->jwtService->validateToken($token);

            if (!$authToken) {
                return $this->unauthorizedResponse('Invalid or expired token');
            }

            $user = User::find($authToken->user->id);

            if (!$user) {
                return $this->unauthorizedResponse('User not found');
            }

            if ($user->deleted_at) {
                return $this->unauthorizedResponse('User account is inactive');
            }

            // if (!$user->email_verified_at) {
            //     return $this->unauthorizedResponse('Email verification required');
            // }

            $request->attributes->set('auth_token', $authToken);

            $this->attachUserToRequest($request, $user, $authToken);

            $this->logSuccessfulAuth($request, $user);

            return $next($request);
        } catch (\Throwable $e) {
            \Log::error('User JWT authentication error', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'error' => $e->getMessage()
            ]);

            return $this->unauthorizedResponse('Authentication failed');
        }
    }

    /**
     * Extraer token del request
     */
    private function extractToken(Request $request): ?string
    {
        $authHeader = $request->header(key: 'X-User-Authorization');

        if (!empty($authHeader) && str_starts_with($authHeader, 'Bearer ')) {
            return trim(substr($authHeader, 7));
        }

        return null;
    }


        /**
     * Adjuntar información del usuario al request
     */
    private function attachUserToRequest(Request $request, User $user, $authToken): void
    {
        $request->attributes->set('authenticated_user', $user);
        $request->attributes->set('auth_token', $authToken);
    }

    /**
     * Log de autenticación exitosa
     */
    private function logSuccessfulAuth(Request $request, User $user): void
    {
        \Log::info('User JWT authenticated successfully', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'ip' => $request->ip(),
            'endpoint' => $request->path(),
            'method' => $request->method()
        ]);
    }

    /**
     * Respuesta de no autorizado
     */
    private function unauthorizedResponse(string $message): ApiResponseResource
    {
        return ResponseHandler::response(401, 'UserAuthMiddleware', $message);
    }
}
