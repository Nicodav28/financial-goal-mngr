<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string|null $gender
 * @property \Carbon\Carbon|null $birth_date
 * @property \Carbon\Carbon|null $email_verified_at
 * @property string $password
 *
 * @property-read \Illuminate\Support\Collection|\App\Models\Group[] $groups
 * @property-read \Illuminate\Support\Collection|\App\Models\Invite[] $sentInvites
 * @property-read \Illuminate\Support\Collection|\App\Models\Invite[] $receivedInvites
 * @property-read \Illuminate\Support\Collection|\App\Models\Goal[] $ownedGoals
 * @property-read \Illuminate\Support\Collection|\App\Models\Goal[] $groupGoals
 */
class User extends Authenticatable
{
    use HasUuids, SoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'birth_date',
        'gender',
        'email_verified_at',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Casts: tipado automático de fechas y otros tipos
     */
    protected $casts = [
        'birth_date'        => 'date',
        'email_verified_at' => 'datetime',
    ];

    /**
     * Groups the user belongs to.
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'user_group', 'user_id', 'group_id')
            ->withTimestamps();
    }

    /**
     * Invites the user has sent.
     */
    public function sentInvites(): HasMany
    {
        return $this->hasMany(Invite::class, 'inviter_id', 'id');
    }

    /**
     * Invites the user has received.
     */
    public function receivedInvites(): HasMany
    {
        return $this->hasMany(Invite::class, 'invitee_id', 'id');
    }

    /**
     * Goals the user owns personally.
     */
    public function ownedGoals(): HasMany
    {
        return $this->hasMany(Goal::class, 'owner_id', 'id');
    }

    /**
     * Goals from the groups the user belongs to.
     */
    public function groupGoals()
    {
        return $this->hasManyThrough(
            Goal::class,
            Group::class,
            'id',       // Foreign key on groups table
            'group_id', // Foreign key on goals table
            'id',       // Local user.id
            'id'        // Local group.id
        );
    }
}
