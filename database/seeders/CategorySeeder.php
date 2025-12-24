<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Politik',
                'slug' => 'politik',
                'is_active' => true,
            ],
            [
                'name' => 'Ekonomi',
                'slug' => 'ekonomi',
                'is_active' => true,
            ],
            [
                'name' => 'Teknologi',
                'slug' => 'teknologi',
                'is_active' => true,
            ],
            [
                'name' => 'Olahraga',
                'slug' => 'olahraga',
                'is_active' => true,
            ],
            [
                'name' => 'Hiburan',
                'slug' => 'hiburan',
                'is_active' => true,
            ],
            [
                'name' => 'Lifestyle',
                'slug' => 'lifestyle',
                'is_active' => true,
            ],
            [
                'name' => 'Internasional',
                'slug' => 'internasional',
                'is_active' => true,
            ],
            [
                'name' => 'Otomotif',
                'slug' => 'otomotif',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
