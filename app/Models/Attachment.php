<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attachment extends Model
{
        use HasUuids, SoftDeletes;

    protected $table = 'attachments';

    protected $fillable = [
        'file_path',
        'original_name',
        'mime_type',
        'size',
        'description',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
