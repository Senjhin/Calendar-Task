<?php

namespace Database\Seeders;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Pobierz wszystkich użytkowników
        $users = User::all();

        // Jeśli brak użytkowników, to nic nie rób
        if ($users->isEmpty()) {
            $this->command->info('No users found, skipping tasks seeding.');
            return;
        }

        // Dla każdego użytkownika utwórz np. 5 tasków
        foreach ($users as $user) {
            Task::factory()
                ->count(5000)
                ->create([
                    'user_id' => $user->id,
                ]);
        }
    }
}
