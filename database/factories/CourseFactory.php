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
            'title' => $this->faker->words(3, true),
            'code' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'description' => $this->faker->text(),
            'credit_hours' => $this->faker->numberBetween(1, 4)
        ];
    }
}
