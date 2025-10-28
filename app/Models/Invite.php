<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invite extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'invites';

    protected $fillable = [
        'inviter_id',
        'invitee_id',
        'group_id',
        'invite_status',
        'invite_code'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    // protected $casts = [
    //     'invite_status' => 'integer',
    // ];
}
