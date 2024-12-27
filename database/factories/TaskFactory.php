<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'assigned_to' => User::factory(),
            'completed_by' => null,
            'notes' => $this->faker->text(),
            'created_by' => User::factory(),
            'project_id' => Project::factory(),
            'status' => $this->faker->randomElement([0, 1, 2, 3]),
            'due_at' => $this->faker->dateTime(),
            'completed_at' => null,
        ];
    }
}
