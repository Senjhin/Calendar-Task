<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskHistory extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'task_id',
        'changed_fields',
        'old_values',
        'new_values',
        'changed_at',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
        ];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }
}
