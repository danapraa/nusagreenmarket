<?php

namespace Tests\Feature\Customer;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_view_product_list_page()
    {
        // 1. Buat Kategori (Wajib untuk database kamu)
        $category = Category::create([
            'name' => 'Sayuran',
            'slug' => 'sayuran',
        ]);

        // 2. Buat Produk
        Product::create([
            'category_id' => $category->id,
            'name' => 'Wortel Segar',
            'description' => 'Wortel manis dari petani lokal',
            'price' => 15000,
            'stock' => 10,
            'image' => 'wortel.jpg',
            'slug' => 'wortel-segar'
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => 'Bayam Hijau',
            'description' => 'Bayam segar bebas pestisida',
            'price' => 5000,
            'stock' => 20,
            'image' => 'bayam.jpg',
            'slug' => 'bayam-hijau'
        ]);

        // 3. User buka halaman produk
        // PERBAIKAN: Sesuai routes/web.php kamu, namanya 'customer.products'
        $response = $this->get(route('customer.products'));

        // 4. Assert
        $response->assertStatus(200);
        $response->assertSee('Wortel Segar');
        $response->assertSee('Bayam Hijau');
    }

    #[Test]
    public function user_can_view_product_detail()
    {
        // 1. Buat Kategori & Produk
        $category = Category::create([
            'name' => 'Buah',
            'slug' => 'buah',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Tomat Merah',
            'description' => 'Tomat matang pohon',
            'price' => 8000,
            'stock' => 15,
            'image' => 'tomat.jpg',
            'slug' => 'tomat-merah'
        ]);

        // 2. Buka detail produk
        // PERBAIKAN: Sesuai routes/web.php kamu, namanya 'customer.products.show'
        $response = $this->get(route('customer.products.show', $product));

        // 3. Assert
        $response->assertStatus(200);
        $response->assertSee('Tomat Merah');
        $response->assertSee('8000');
    }
}
