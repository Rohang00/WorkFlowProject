<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemberFactory extends Factory
{
    protected $model = Member::class;

    public function definition()
    {
        return [
            'role' => $this->faker->randomElement([1, 2, 3]),
            'status' => $this->faker->randomElement([0, 1]),
            'user_id' => User::factory(),
            'org_id' => Organization::factory(),
        ];
    }
}
