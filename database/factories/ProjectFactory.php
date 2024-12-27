<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'slug' => $this->faker->unique()->slug(),
            'description' => $this->faker->paragraph(),
            'deadline' => $this->faker->date(),
            'created_by' => User::factory(),
            'org_id' => Organization::factory(),
        ];
    }
}
