<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        User::all()->each(function ($user) {
            Task::factory()
                ->count(100)
                ->for($user)
                ->create();
        });
    }
}
