<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'Mateuszkordysinvestments@gmail.com'],
            [
                'name' => 'Mateusz',
                'password' => Hash::make('Haslo123'),
            ]
        );
        $this->call(TaskSeeder::class);
    }
}
