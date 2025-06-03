<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use Notifiable, hasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_token',
        'google_refresh_token',
        'google_token_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'google_token',
        'google_refresh_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'google_token_expires_at' => 'datetime',
    ];

    // Relacja 1:N - użytkownik ma wiele zadań
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    // Relacja 1:N - historia zmian dokonanych przez użytkownika
    public function taskHistories(): HasMany
    {
        return $this->hasMany(TaskHistory::class);
    }

    public function googleEvents()
    {
        return $this->hasManyThrough(GoogleEvent::class, Task::class);
    }
}
