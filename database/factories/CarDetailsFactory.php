<?php

namespace Database\Factories;

use App\Models\Car;
use App\Models\CarDetails;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CarDetails>
 */
class CarDetailsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = CarDetails::class;

    public function definition(): array
    {
        return [
            'Model' => fake()->word,
            'Color' => fake()->safeColorName,
            'Hybrid' => fake()->boolean,
            'Electric' => fake()->boolean,
            'AirConditioner' => fake()->boolean,
            'RegistrationNumber' => fake()->regexify('[A-Z0-9]{6}'),
            'Mileage' => fake()->numberBetween(1000, 200000),
            'GPSInstalled' => fake()->boolean,
            'BluetoothEnabled' => fake()->boolean,
            'InsuranceDetails' => fake()->sentence,
            'MaintenanceHistory' => fake()->paragraph,
            'CarID' => function () {
                return Car::factory()->create()->id;
            }
        ];
    }
}
