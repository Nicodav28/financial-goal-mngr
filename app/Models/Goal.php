<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Goal extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'goals';

    protected $fillable = [
        'currency_id',
        'title',
        'description',
        'target_amount',
        'status',
        'due_date',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
