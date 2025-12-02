<?php

namespace Database\Factories;

use App\Models\Door;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Door>
 */
class DoorFactory extends Factory
{
    protected $model = Door::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'image_path' => 'doors/' . fake()->uuid() . '.jpg',
        ];
    }
}
