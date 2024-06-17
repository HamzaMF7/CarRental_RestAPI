<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Car;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Car::class;

    public function definition(): array
    {
        return [
            'CarName' => fake()->word,
            'Price' => fake()->numberBetween(10000, 50000),
            'Capacity' => fake()->numberBetween(2, 7),
            'Image' => fake()->imageUrl(640, 480, 'cars', true),
            'FuelType' => fake()->randomElement(['diesel', 'essence', 'electric', 'hybrid/essence', 'hybrid/diesel']),
            'TransmissionType' => fake()->randomElement(['Manual', 'Automatic']),
            'CurrentStatus' => fake()->randomElement([
                'Available',
                'Unavailable',

            ]),
            'CategoryID' => Category::inRandomOrder()->first()->id ?? Category::factory(),
            'BrandID' => Brand::inRandomOrder()->first()->id ?? Brand::factory(),
            'LocationID' => Location::inRandomOrder()->first()->id ?? Location::factory(),
        ];
    }
}
