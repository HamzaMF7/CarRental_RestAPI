<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {

        $cities = ['Casablanca', 'Settat', 'Marrakech', 'Rabat', 'Tanger', 'Agadir', 'Beni Mellal', 'kenitra'];

        foreach ($cities as $city) {
            Location::factory()->create([
                'City' => $city,
            ]);     
        }
    }
}
