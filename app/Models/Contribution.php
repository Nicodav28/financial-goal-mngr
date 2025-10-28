<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contribution extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'contributions';

    protected $fillable = [
        'user_id',
        'goal_id',
        'currency_id',
        'amount',
        'note',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
