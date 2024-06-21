<?php

namespace Database\Factories;

use App\Models\Car;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'Rating' => fake()->numberBetween(1, 5),
            'Comment' => fake()->sentence,
            'DatePosted' => fake()->dateTimeBetween('-2 years', 'now'),
            'UserID' => User::inRandomOrder()->first()->id ?? User::factory(),
            'CarID' => Car::inRandomOrder()->first()->id ?? Car::factory(),
        ];
    }
}
