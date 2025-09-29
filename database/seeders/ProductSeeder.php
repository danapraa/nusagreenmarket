<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'category_id' => 1,
                'name' => 'Sawi Hijau Organik',
                'slug' => 'sawi-hijau-organik',
                'description' => 'Sawi hijau segar tanpa pestisida, kaya nutrisi dan vitamin. Cocok untuk tumisan dan sup.',
                'price' => 8000,
                'stock' => 50,
                'unit' => 'kg',
                'origin' => 'Kebun A',
                'harvest_date' => Carbon::today(),
                'badge' => 'FRESH',
                'is_active' => true,
            ],
            [
                'category_id' => 2,
                'name' => 'Wortel Manis',
                'slug' => 'wortel-manis',
                'description' => 'Wortel manis berkualitas premium, tekstur renyah dan kandungan vitamin A tinggi.',
                'price' => 12000,
                'stock' => 40,
                'unit' => 'kg',
                'origin' => 'Kebun B',
                'harvest_date' => Carbon::yesterday(),
                'badge' => 'BEST SELLER',
                'is_active' => true,
            ],
            [
                'category_id' => 3,
                'name' => 'Tomat Cherry',
                'slug' => 'tomat-cherry',
                'description' => 'Tomat cherry manis dan segar, sempurna untuk salad dan camilan sehat keluarga.',
                'price' => 15000,
                'stock' => 30,
                'unit' => 'kg',
                'origin' => 'Kebun C',
                'harvest_date' => Carbon::today(),
                'badge' => 'ORGANIC',
                'is_active' => true,
            ],
            [
                'category_id' => 3,
                'name' => 'Timun Jepang',
                'slug' => 'timun-jepang',
                'description' => 'Timun jepang segar dan renyah, cocok untuk lalapan dan asinan. Tanpa bahan kimia.',
                'price' => 10000,
                'stock' => 45,
                'unit' => 'kg',
                'origin' => 'Kebun D',
                'harvest_date' => Carbon::today(),
                'badge' => 'NEW',
                'is_active' => true,
            ],
            [
                'category_id' => 4,
                'name' => 'Cabai Rawit Merah',
                'slug' => 'cabai-rawit-merah',
                'description' => 'Cabai rawit merah pilihan dengan tingkat kepedasan pas. Ideal untuk bumbu masakan.',
                'price' => 35000,
                'stock' => 20,
                'unit' => 'kg',
                'origin' => 'Kebun E',
                'harvest_date' => Carbon::yesterday(),
                'badge' => 'PREMIUM',
                'is_active' => true,
            ],
            [
                'category_id' => 5,
                'name' => 'Brokoli Segar',
                'slug' => 'brokoli-segar',
                'description' => 'Brokoli segar dengan kualitas ekspor. Kaya nutrisi dan antioksidan untuk kesehatan keluarga.',
                'price' => 20000,
                'stock' => 25,
                'unit' => 'kg',
                'origin' => 'Kebun F',
                'harvest_date' => Carbon::today(),
                'badge' => 'SEASONAL',
                'is_active' => true,
            ],
            [
                'category_id' => 1,
                'name' => 'Bayam Merah',
                'slug' => 'bayam-merah',
                'description' => 'Bayam merah organik kaya zat besi, cocok untuk sayur bening dan pecel.',
                'price' => 7000,
                'stock' => 35,
                'unit' => 'kg',
                'origin' => 'Kebun A',
                'harvest_date' => Carbon::today(),
                'badge' => 'ORGANIC',
                'is_active' => true,
            ],
            [
                'category_id' => 1,
                'name' => 'Kangkung Darat',
                'slug' => 'kangkung-darat',
                'description' => 'Kangkung darat segar, renyah dan lezat untuk tumisan.',
                'price' => 6000,
                'stock' => 60,
                'unit' => 'kg',
                'origin' => 'Kebun B',
                'harvest_date' => Carbon::today(),
                'badge' => 'FRESH',
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
