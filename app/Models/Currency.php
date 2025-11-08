<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'currencies';

    protected $fillable = [
        'name',
        'symbol',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
