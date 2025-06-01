<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use App\Models\Task;
use App\Notifications\TaskReminderNotification;
use Carbon\Carbon;

class SendTaskReminders extends Command
{
    protected $signature = 'tasks:send-reminders';
    protected $description = 'Wysyła przypomnienia o zadaniach na jutro.';

    public function handle(): int
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        $tasks = Task::with('user')
            ->whereDate('due_date', $tomorrow)
            ->whereIn('status', ['to-do', 'in progress'])
            ->get();

        foreach ($tasks as $task) {
            if ($task->user && $task->user->email) {
                $task->user->notify(new TaskReminderNotification($task));
            }
        }

        $this->info("Wysłano przypomnienia dla " . count($tasks) . " zadań.");
        return self::SUCCESS;
    }

    public function schedule(Schedule $schedule): void
    {
        $schedule->command(static::class)->everyFiveMinutes();
    }
}
