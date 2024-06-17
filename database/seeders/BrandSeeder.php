<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            'Volkswagen',
            'Dacia',
            'Renault',
            'Toyota',
            'Ford',
            'Bmw',
            'Mercedes',
            'Nissan',
        ];

        foreach ($brands as $brand) {
            Brand::create(['BrandName' => $brand]);
        }
    }
}
