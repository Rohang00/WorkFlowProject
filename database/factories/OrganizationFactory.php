<?php

namespace Database\Factories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrganizationFactory extends Factory
{
    protected $model = Organization::class;

    public function definition()
    {
        return [
            'avatar' => $this->faker->imageUrl(),
            'name' => $this->faker->company(),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'description' => $this->faker->paragraph(),
        ];
    }
}
