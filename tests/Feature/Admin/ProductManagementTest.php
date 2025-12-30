<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    // --- HELPERS ---

    private function createAdmin()
    {
        return User::create([
            'name' => 'Admin Produk',
            'email' => 'admin.prod@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'phone' => '08123456700',
        ]);
    }

    private function createCustomer()
    {
        return User::create([
            'name' => 'Customer Biasa',
            'email' => 'cust@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'phone' => '08123456789',
        ]);
    }

    private function createCategory()
    {
        return Category::create([
            'name' => 'Sayuran',
            'slug' => 'sayuran',
            'icon' => '🥬'
        ]);
    }

    // --- TESTS ---

    #[Test]
    public function admin_can_access_product_list_page()
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();

        // Buat produk dummy
        Product::create([
            'name' => 'Kangkung',
            'slug' => 'kangkung-segar',
            'category_id' => $category->id,
            'price' => 2000,
            'stock' => 50,
            'description' => 'Kangkung hidroponik',
            'unit' => 'ikat'
        ]);

        $response = $this->actingAs($admin)->get(route('admin.products.index'));

        $response->assertStatus(200);
        $response->assertSee('Kangkung');
        $response->assertSee('2.000'); // Format harga (sesuaikan dengan tampilan view Anda)
    }

    #[Test]
    public function admin_can_create_new_product_with_image()
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();

        // Simulasi Storage agar file tidak benar-benar tersimpan di folder public
        Storage::fake('public');

        // Data Input
        $productData = [
            'name' => 'Tomat Merah',
            'category_id' => $category->id,
            'price' => 8000,
            'stock' => 20,
            'description' => 'Tomat merah segar langsung dari kebun.',
            'unit' => 'kg',
            'badge' => 'FRESH', // Opsional, jika ada kolom badge
            // Upload gambar palsu
            'thumbnail' => UploadedFile::fake()->image('tomat.jpg'),
        ];

        // Action: Post
        $response = $this->actingAs($admin)->post(route('admin.products.store'), $productData);

        // Assert Redirect
        $response->assertStatus(302);
        $response->assertRedirect(route('admin.products.index'));

        // Assert Database
        $this->assertDatabaseHas('products', [
            'name' => 'Tomat Merah',
            'price' => 8000,
            'slug' => 'tomat-merah', // Pastikan controller Anda generate slug otomatis
        ]);
    }

    #[Test]
    public function admin_can_update_existing_product()
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();

        // 1. Buat produk awal
        $product = Product::create([
            'name' => 'Cabe Rawit',
            'slug' => 'cabe-rawit',
            'category_id' => $category->id,
            'price' => 50000,
            'stock' => 5,
            'description' => 'Pedas nampol',
            'unit' => 'kg'
        ]);

        // 2. Data Update (Ganti harga dan stok)
        $updateData = [
            'name' => 'Cabe Rawit Merah', // Ganti nama
            'category_id' => $category->id,
            'price' => 60000, // Harga naik
            'stock' => 10,    // Stok nambah
            'description' => 'Pedas nampol banget',
            'unit' => 'kg',
            // Tidak upload gambar baru (simulasi update text saja)
        ];

        // 3. Action: Put/Patch
        $response = $this->actingAs($admin)->put(route('admin.products.update', $product), $updateData);

        // 4. Assert
        $response->assertStatus(302);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Cabe Rawit Merah',
            'price' => 60000,
            'stock' => 10,
        ]);
    }

    #[Test]
    public function admin_can_delete_product()
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();

        // 1. Buat produk
        $product = Product::create([
            'name' => 'Produk Gagal',
            'slug' => 'produk-gagal',
            'category_id' => $category->id,
            'price' => 1000,
            'stock' => 1,
            'description' => 'Akan dihapus',
            'unit' => 'pcs'
        ]);

        // 2. Action: Delete
        $response = $this->actingAs($admin)->delete(route('admin.products.destroy', $product));

        // 3. Assert
        $response->assertStatus(302);

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }

    #[Test]
    public function customer_cannot_manage_products()
    {
        $customer = $this->createCustomer();
        $category = $this->createCategory();
        $product = Product::create([
            'name' => 'Produk Admin',
            'slug' => 'produk-admin',
            'category_id' => $category->id,
            'price' => 1000,
            'stock' => 1,
            'description' => '...',
            'unit' => 'kg'
        ]);

        // Coba akses index
        $response1 = $this->actingAs($customer)->get(route('admin.products.index'));
        $response1->assertStatus(403);

        // Coba hapus produk
        $response2 = $this->actingAs($customer)->delete(route('admin.products.destroy', $product));
        $response2->assertStatus(403);
    }
}
