<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TaskShare extends Model
{
    protected $fillable = ['task_id', 'token', 'expires_at'];

    protected $dates = ['expires_at'];

    public static function generateToken()
    {
        return Str::random(32);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
