<?php

namespace Database\Factories;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    
     protected $model = Task::class;

    public function definition()
    {
        $priorities = ['low', 'medium', 'high'];
        $statuses = ['to-do', 'in-progress', 'done'];

        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'priority' => $this->faker->randomElement($priorities),
            'status' => $this->faker->randomElement($statuses),
            'due_date' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'user_id' => 1, // tutaj możesz podać ID istniejącego użytkownika albo dodać generator userów
        ];
    }
}
