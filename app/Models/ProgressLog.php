<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgressLog extends Model
{
    use HasUuids;

    protected $table = 'progress_logs';

    protected $fillable = [
        'goal_id',
        'amount',
        'recorded_at',
    ];

    public $timestamps = false; // using recorded_at instead

    public function goal(): BelongsTo
    {
        return $this->belongsTo(Goal::class, 'goal_id');
    }
}
