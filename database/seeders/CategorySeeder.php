<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Sayuran Hijau',
                'slug' => 'sayuran-hijau',
                'description' => 'Berbagai jenis sayuran hijau segar',
                'icon' => '🥬',
                'is_active' => true,
            ],
            [
                'name' => 'Sayuran Akar',
                'slug' => 'sayuran-akar',
                'description' => 'Wortel, kentang, dan sayuran akar lainnya',
                'icon' => '🥕',
                'is_active' => true,
            ],
            [
                'name' => 'Sayuran Buah',
                'slug' => 'sayuran-buah',
                'description' => 'Tomat, terong, dan sayuran buah lainnya',
                'icon' => '🍅',
                'is_active' => true,
            ],
            [
                'name' => 'Cabai & Bumbu',
                'slug' => 'cabai-bumbu',
                'description' => 'Berbagai jenis cabai dan bumbu dapur',
                'icon' => '🌶️',
                'is_active' => true,
            ],
            [
                'name' => 'Sayuran Premium',
                'slug' => 'sayuran-premium',
                'description' => 'Sayuran organik premium berkualitas tinggi',
                'icon' => '🥦',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
