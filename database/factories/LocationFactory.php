<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Location::class;

    public function definition(): array
    {
        return [
            'Name' => $this->faker->company . ' ' . $this->faker->randomElement(['Branch', 'Office', 'Center']),
            'Address' => $this->faker->streetAddress,
            'City' => $this->faker->city,
            'State' => $this->faker->state,
            'ZipCode' => $this->faker->postcode,
            'PhoneNumber' => $this->faker->phoneNumber,
        ];
    }
}
