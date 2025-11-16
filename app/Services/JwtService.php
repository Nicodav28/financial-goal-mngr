<?php

namespace App\Services;

use App\Dtos\AuthTokenDTO;
use App\Models\Token;
use App\Models\User;
use App\Repositories\Contracts\IAuthTokenRepository;
use Auth;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\UnauthorizedException;
use RuntimeException;
use Illuminate\Http\Request;

class JwtService
{

    private string $privateKey;
    private string $publicKey;
    private string $jwtValidityTime;
    private string $jwtTypeTime;
    private string $jwtPassphrase;

    public function __construct(
        private readonly IAuthTokenRepository $authTokenRepository,
        private readonly Request $request

    ) {
        $this->loadInitialAuthParams();
    }

    public function generateToken(User $user): ?string
    {
        $jti = Str::uuid()->toString();
        $now = Carbon::now();
        $expiresAt = $now->copy()->add((int) $this->jwtValidityTime, $this->jwtTypeTime);

        $payload = $this->createJwtPayload(
            $user,
            $jti,
            $now->timestamp,
            $expiresAt->timestamp,
            null,
            null
        );

        $jwtToken = JWT::encode(
            $payload,
            openssl_pkey_get_private($this->privateKey, $this->jwtPassphrase),
            'RS256'
        );


        $tokenDTO = new AuthTokenDTO(
            $jti,
            $user->id,
            'jwt',
            '',
            $this->request->ip(),
            '',
            '',
            $this->request->userAgent(),
            true,
            $expiresAt,
            $now,
            null
        );

        $this->authTokenRepository->revokeAllUserTokens($user->id);

        $token = $this->authTokenRepository->createTokens($tokenDTO);

        if(!\is_object($token)){
            return null;
        }

        return $jwtToken;
    }

    public function validateToken(string $token)
    {
        try {
            $decoded = $this->decodeToken($token);

            if ($decoded['iss'] !== config('app.url')) {
                throw new UnauthorizedException('Invalid token issuer.');
            }

            $authToken = $this->authTokenRepository->findTokenByJtiAndUserId($decoded['jti'], $decoded['sub'], 'valid');

            if (!$authToken) {
                throw new UnauthorizedException('Invalid JWT.');
            }

            $authToken->load('user');
            Auth::setUser($authToken->user);

            return $authToken;
        } catch (\Throwable $th) {
            throw new RuntimeException('JWT validation failed!', 0, $th);
        }
    }

    public function invalidateToken(Token $token): bool
    {
        try {
            return $this->authTokenRepository->revokeToken($token->id);
        } catch (\Throwable $th) {
            throw new RuntimeException('JWT validation failed!', 0, $th);
        }
    }

    public function refreshToken(string $token): string
    {
        return $token;
    }

    public function isTokenValid(string $token): bool
    {
        return true;
    }

    private function createJwtPayload(User $user, string $jti, int|float|string $timestampIat, int|float|string $timestampExp, ?string $audience, ?array $extraParams): array
    {
        $currentTime = Carbon::now();
        $payload = [
            'sub' => $user->id,
            //            'roles' => array_map(fn($role) => $role['name'], $user->getRoles()),
            'iat' => $timestampIat,
            'exp' => $timestampExp,
            'aud' => $audience,
            'iss' => config('app.url'),
            'jti' => $jti,
        ];

        if ($extraParams) {
            $payload['data'] = Crypt::encrypt(json_encode($extraParams, JSON_UNESCAPED_SLASHES));
        }

        return $payload;
    }

    private function loadInitialAuthParams(): void
    {
        $this->privateKey = Storage::disk('private')->get('keys/' . config('app.user_auth.private_key_path'));
        $this->publicKey = Storage::disk('private')->get('keys/' . config('app.user_auth.public_key_path'));

        if (empty($this->privateKey) || empty($this->publicKey)) {
            throw new RuntimeException('Authenticate keys are not defined for user auth.');
        }

        $this->jwtValidityTime = config('app.user_auth.jwt_validity_time');
        $this->jwtTypeTime = config('app.user_auth.jwt_type_time');

        if (empty($this->jwtValidityTime) || empty($this->jwtTypeTime)) {
            throw new RuntimeException('JWT validate time config values are not defined for user auth.');
        }

        $this->jwtPassphrase = config('app.user_auth.private_key_passphrase');

        if (empty($this->jwtPassphrase)) {
            throw new RuntimeException('JWT Passphrase not configured.');
        }
    }

    private function decodeToken(string $token): array
    {
        $decoded = JWT::decode($token, new Key($this->publicKey, 'RS256'));
        return (array) $decoded;
    }

}
