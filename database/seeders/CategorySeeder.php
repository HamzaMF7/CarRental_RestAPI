<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Sport',
            'SUV',
            'MPV',
            'Sedan',
            'Coupe',
            'Hatchback'  // Note: Fixed the typo from 'Hatckback' to 'Hatchback'
        ];

        foreach ($categories as $category) {
            Category::create(['CategoryName' => $category]);
        }
    }
}
