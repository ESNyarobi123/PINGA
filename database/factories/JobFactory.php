<?php

namespace Database\Factories;

use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    protected $model = Job::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $budgetType = fake()->randomElement(['fixed', 'hourly']);
        $min = fake()->numberBetween(20000, 500000);
        $max = $budgetType === 'hourly'
            ? $min + fake()->numberBetween(5000, 30000)
            : $min + fake()->numberBetween(50000, 500000);

        return [
            'employer_id' => User::factory(),
            'category_id' => null,
            'title' => fake()->sentence(4),
            'description' => fake()->paragraphs(3, true),
            'requirements' => fake()->optional(0.7)->paragraphs(2, true),
            'location' => fake()->city(),
            'latitude' => fake()->optional(0.5)->latitude(),
            'longitude' => fake()->optional(0.5)->longitude(),
            'budget_min' => $min,
            'budget_max' => $max,
            'budget_type' => $budgetType,
            'duration' => fake()->randomElement(['siku 1', 'wiki 1', 'wiki 2', 'mwezi 1', 'miezi 2']),
            'status' => 'open',
            'urgency' => fake()->randomElement(['normal', 'urgent', 'very_urgent']),
            'remote_allowed' => fake()->boolean(25),
            'views_count' => fake()->numberBetween(0, 200),
            'applications_count' => fake()->numberBetween(0, 15),
        ];
    }

    public function open(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'open']);
    }

    public function urgent(): static
    {
        return $this->state(fn (array $attributes) => ['urgency' => 'urgent']);
    }

    public function veryUrgent(): static
    {
        return $this->state(fn (array $attributes) => ['urgency' => 'very_urgent']);
    }
}
