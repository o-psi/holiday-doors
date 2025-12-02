<?php

namespace Database\Factories;

use App\Models\Door;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vote>
 */
class VoteFactory extends Factory
{
    protected $model = Vote::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'voter_name' => fake()->name(),
            'door_id' => Door::factory(),
            'rank' => fake()->numberBetween(1, 3),
        ];
    }

    /**
     * Indicate that the vote is a first place vote.
     */
    public function firstPlace(): static
    {
        return $this->state(fn (array $attributes) => [
            'rank' => 1,
        ]);
    }

    /**
     * Indicate that the vote is a second place vote.
     */
    public function secondPlace(): static
    {
        return $this->state(fn (array $attributes) => [
            'rank' => 2,
        ]);
    }

    /**
     * Indicate that the vote is a third place vote.
     */
    public function thirdPlace(): static
    {
        return $this->state(fn (array $attributes) => [
            'rank' => 3,
        ]);
    }
}
