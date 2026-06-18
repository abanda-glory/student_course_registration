<?php

namespace Database\Seeders;

use Database\Seeders\UserSeeder;
use Database\Seeders\CourseSeeder;
use Database\Seeders\AdminSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(
            [
                AdminSeeder::class,
                UserSeeder::class,
                CourseSeeder::class,
            ]
        );
    }
}
