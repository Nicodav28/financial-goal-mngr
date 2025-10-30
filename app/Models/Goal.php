<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Goal extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'goals';

    protected $fillable = [
        'owner_id',
        'group_id',
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

    // public function currency()
    // {
    //     return $this->belongsTo(Currency::class);
    // }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function participants(): BelongsTo|BelongsToMany
    {
        if ($this->group_id) {
            return $this->group->users();
        }

        return $this->owner();
    }
}
