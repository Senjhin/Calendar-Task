<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'status' => $this->faker->randomElement(['to-do', 'in progress', 'done']),
            'due_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'google_synced' => $this->faker->boolean(25),
        ];
    }
}
