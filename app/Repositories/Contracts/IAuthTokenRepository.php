<?php

namespace App\Repositories\Contracts;

use App\Dtos\AuthTokenDTO;
use App\Models\Token;
use App\Models\User;

interface IAuthTokenRepository
{
    /**
     * Crea tokens de acceso (JWT) y refresh token para un usuario
     *
     * @param AuthTokenDTO $tokenData
     * @return array ['access_token' => string, 'refresh_token' => string, 'refresh_token_model' => Token]
     */
    public function createTokens(AuthTokenDTO $tokenData): Token;

    /**
     * Revoca un refresh token por su ID
     */
    public function revokeToken(string $tokenId): bool;

    /**
     * Revoca todos los refresh tokens activos de un usuario
     */
    public function revokeAllUserTokens(string $userId): int;

    /**
     * Verifica un refresh token y devuelve el modelo si es válido
     */
    public function verifyRefreshToken(User $user, string $refreshTokenPlain): ?Token;

    public function findTokenByJtiAndUserId(string $jti, string $userId, string $status): ?Token;
}
