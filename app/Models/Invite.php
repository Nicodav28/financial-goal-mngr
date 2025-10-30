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
        'inviter_id', //usuario que invita
        'invitee_id', //usuario invitado
        'group_id', //grupo al que se invita
        'invite_status',
        'invite_code'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function inviter()
    {
        return $this->belongsTo(User::class, 'inviter_id', 'id');
    }

    public function invitee()
    {
        return $this->belongsTo(User::class, 'invitee_id', 'id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }
}
