<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoogleEvent extends Model
{
    protected $fillable = [
        'task_id',
        'google_event_id',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
