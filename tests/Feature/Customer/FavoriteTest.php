<?php

namespace Tests\Feature\Customer;

use App\Models\Category;
use App\Models\Favorite;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper untuk membuat user customer yang valid (profil lengkap).
     */
    private function createCustomer()
    {
        return User::create([
            'name' => 'Customer Fav',
            'email' => 'fav@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'phone' => '08123456789',
            'address' => 'Jl. Mawar No 10',
            'profile_completed' => true,
        ]);
    }

    /**
     * Helper untuk membuat produk dummy.
     */
    private function createProduct()
    {
        $category = Category::create(['name' => 'Buah', 'slug' => 'buah']);

        return Product::create([
            'name' => 'Mangga Harum Manis',
            'slug' => 'mangga-harum-manis',
            'price' => 25000,
            'stock' => 50,
            'category_id' => $category->id,
            'description' => 'Mangga manis asli.', // Wajib diisi agar tidak error NOT NULL
            'unit' => 'kg',
        ]);
    }

    #[Test]
    public function customer_can_access_favorites_page()
    {
        $user = $this->createCustomer();

        $response = $this->actingAs($user)->get(route('customer.favorites.index'));

        $response->assertStatus(200);
        // Pastikan ada teks yang sesuai dengan view Anda (misal: "Favorit Saya" atau "Wishlist")
        // Sesuaikan string di bawah ini dengan judul halaman di view Anda
        $response->assertSee('Favorit');
    }

    #[Test]
    public function customer_can_add_product_to_favorites()
    {
        $user = $this->createCustomer();
        $product = $this->createProduct();

        // Action: Post ke route add favorite
        $response = $this->actingAs($user)->post(route('customer.favorites.add', $product));

        // Assert: Redirect back & Data masuk DB
        $response->assertStatus(302);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    #[Test]
    public function customer_cannot_add_same_product_twice()
    {
        $user = $this->createCustomer();
        $product = $this->createProduct();

        // 1. Tambahkan sekali
        Favorite::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        // 2. Coba tambahkan lagi produk yang sama via route
        $response = $this->actingAs($user)->from(route('customer.products')) // pura-pura dari halaman produk
             ->post(route('customer.favorites.add', $product));

        // 3. Pastikan redirect dan ada pesan error/info (sesuai controller Anda)
        $response->assertStatus(302);

        // Pastikan di database tetap hanya ada 1 record, bukan 2
        $this->assertCount(1, Favorite::where('user_id', $user->id)->where('product_id', $product->id)->get());
    }

    #[Test]
    public function customer_can_remove_product_from_favorites()
    {
        $user = $this->createCustomer();
        $product = $this->createProduct();

        // Setup: Sudah ada di favorit
        $favorite = Favorite::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        // Action: Hapus
        $response = $this->actingAs($user)->delete(route('customer.favorites.remove', $favorite));

        // Assert: Redirect & Data hilang dari DB
        $response->assertStatus(302);

        $this->assertDatabaseMissing('favorites', [
            'id' => $favorite->id,
        ]);
    }
}
