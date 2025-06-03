<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Task extends Model
{
   use hasFactory;
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'priority',
        'status',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'google_synced' => 'boolean',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function taskShares()
    {
        return $this->hasMany(TaskShare::class);
    }


    public function taskHistories()
    {
        return $this->hasMany(TaskHistory::class);
    }

    public function googleEvent(): HasOne
    {
        return $this->hasOne(GoogleEvent::class);
    }
}
