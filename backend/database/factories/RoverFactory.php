<?php

namespace Database\Factories;

use App\Enums\Direction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rover>
 */
class RoverFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'x' => 0, 'y' => 0, 'direction' => Direction::NORTH
        ];
    }
}
