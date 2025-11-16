<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Token extends Model
{
    use SoftDeletes, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'tokens';

    protected $fillable = [
        'id',
        'user_id',
        'type',
        'token_hash',
        'ip',
        'location',
        'device',
        'user_agent',
        'is_trusted',
        'expires_at',
        'last_used_at',
        'revoked_at',
    ];

    protected $casts = [
        'is_trusted' => 'boolean',
        'expires_at' => 'datetime',
        'last_used_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * Verificar si el token está activo
     */
    public function isActive(): bool
    {
        return !$this->revoked_at && (!$this->expires_at || $this->expires_at->isFuture());
    }
}
