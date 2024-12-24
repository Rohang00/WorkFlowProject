<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'avatar' => $this->faker->imageUrl(),
            'is_active' => $this->faker->boolean(),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'address' => $this->faker->address(),
            'contact' => $this->faker->phoneNumber(),
            'last_login_at' => $this->faker->dateTime(),
        ];
    }
}
