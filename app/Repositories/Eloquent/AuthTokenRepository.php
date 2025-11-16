<?php

namespace App\Repositories\Eloquent;

use App\Dtos\AuthTokenDTO;
use App\Models\Token;
use App\Models\User;
use App\Repositories\Contracts\IAuthTokenRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthTokenRepository implements IAuthTokenRepository
{
    /**
     * Crea un token de acceso (JWT) y un refresh token para un usuario
     *
     * @param User $user
     * @param string $deviceName
     * @param string|null $location
     * @param string|null $ipAddress
     * @param string|null $userAgent
     * @param bool $isTrusted
     * @return array ['access_token' => string, 'refresh_token_model' => Token]
     */
    public function createTokens(AuthTokenDTO $tokenDTO): Token
    {
        return Token::create($tokenDTO->toArray());
    }

    /**
     * Revoca un refresh token
     */
    public function revokeToken(string $tokenId): bool
    {
        $tokenModel = Token::find($tokenId);

        if (!$tokenModel) {
            return false;
        }

        $tokenModel->update([
            'revoked_at'   => Carbon::now(),
        ]);

        return true;
    }

    /**
     * Revoca todos los refresh tokens activos de un usuario
     */
    public function revokeAllUserTokens(string $userId): int
    {
        return Token::where('user_id', $userId)
            ->whereNull('revoked_at')
            ->update([
                'revoked_at' => Carbon::now(),
                'expires_at' => Carbon::now(),
            ]);
    }

    /**
     * Verifica un refresh token recibido y devuelve el modelo si es válido
     */
    public function verifyRefreshToken(User $user, string $refreshTokenPlain): ?Token
    {
        $tokenModel = Token::where('user_id', $user->id)
            ->where('type', 'refresh')
            ->whereNull('revoked_at')
            ->where('expires_at', '>', Carbon::now())
            ->get()
            ->first(function ($token) use ($refreshTokenPlain) {
                return Hash::check($refreshTokenPlain, $token->token_hash);
            });

        if ($tokenModel) {
            $tokenModel->update(['last_used_at' => Carbon::now()]);
            return $tokenModel;
        }

        return null;
    }

    public function findTokenByJtiAndUserId(string $jti, string $userId, string $status): ?Token
    {
        $token = Token::where('id', $jti)
            ->where('user_id', $userId)
            ->where('revoked_at', '=' , null)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$token) {
            return null;
        }

        $token->load('user');

        return $token;
    }
}
