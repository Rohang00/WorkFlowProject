<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use  Database\Seeders\{UserSeeder, MemberSeeder, OrganizationSeeder, ProjectSeeder, TaskSeeder};
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            MemberSeeder::class,
            OrganizationSeeder::class,
            ProjectSeeder::class,
            TaskSeeder::class,
        ]);
    }
}
