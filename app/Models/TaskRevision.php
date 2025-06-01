<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskRevision extends Model
{
    // Nazwa tabeli (opcjonalnie, jeśli nazwa jest inna niż 'task_revisions')
    protected $table = 'task_revisions';

    // Pola do masowego przypisywania
    protected $fillable = [
        'task_id',
        'user_id',
        'field_name',
        'old_values',
        'new_values',
    ];

    // Automatyczne zarządzanie timestampami (created_at, updated_at)
    public $timestamps = true;

    // Relacja do zadania (Task)
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    // Relacja do użytkownika (User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
