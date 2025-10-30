<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'birth_date',
        'gender',
        'email_verified_at',
        'password'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'user_group', 'user_id', 'group_id')
            ->withTimestamps();
    }

    public function sentInvites(): HasMany
    {
        return $this->hasMany(Invite::class, 'inviter_id', 'id');
    }

    public function receivedInvites(): HasMany
    {
        return $this->hasMany(Invite::class, 'invitee_id', 'id');
    }

    public function ownedGoals(): HasMany
    {
        return $this->hasMany(Goal::class, 'owner_id', 'id');
    }

    public function groupGoals()
    {
        return $this->hasManyThrough(
            Goal::class,
            Group::class,
            'id',
            'group_id',
            'id',
            'id'
        );
    }
}
