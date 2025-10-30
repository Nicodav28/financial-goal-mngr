<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'groups';

    protected $fillable = [
        'name',
        'description',
        'created_by',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_group', 'group_id', 'user_id')
                    ->withTimestamps();
    }

    public function invites(): HasMany
    {
        return $this->hasMany(Invite::class, 'group_id', 'id');
    }

    public function goals(): HasMany
    {
        return $this->hasMany(Goal::class, 'group_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
