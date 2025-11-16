<?php

namespace App\Dtos;

use Carbon\Carbon;

class AuthTokenDTO{
    public function __construct(
        public string $jti,
        public string $userId,
        public string $type,
        public string $tokenHash,
        public string $ip,
        public string $location,
        public string $deviceName,
        public string $userAgent,
        public string $isTrusted,
        public Carbon $expiresAt,
        public Carbon $lastUsedAt,
        public Carbon|null $revokedAt
    ){}

    public function toArray(){
        return [
            'id'          => $this->jti,
            'user_id'     => $this->userId,
            'type'        => $this->type,
            'token_hash'  => $this->tokenHash,
            'ip'          => $this->ip,
            'location'    => $this->location,
            'device'      => $this->deviceName,
            'user_agent'  => $this->userAgent,
            'is_trusted'  => $this->isTrusted,
            'expires_at'  => $this->expiresAt,
            'last_used_at'=> $this->lastUsedAt,
            'revoked_at'  => $this->revokedAt,
        ];
    }
}
