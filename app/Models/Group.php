<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
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
}
