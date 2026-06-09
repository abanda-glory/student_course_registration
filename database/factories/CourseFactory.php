<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->words(3, true),
            'code' => fake()->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'description' => fake()->text(),
            'credit_hours' => fake()->numberBetween(1, 4)
        ];
    }
}
