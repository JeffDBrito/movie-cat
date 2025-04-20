<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Filme>
 */
class FilmeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tmdb_id' => $this->faker->unique()->numberBetween(1, 10000),
            'title' => $this->faker->sentence(),
            'poster_path' => $this->faker->imageUrl(),
            'tmdb_details' => [
                'overview' => $this->faker->paragraph(),
                'release_date' => $this->faker->date(),
                'vote_average' => $this->faker->randomFloat(2, 0, 10),
            ],
        ];
    }
}
