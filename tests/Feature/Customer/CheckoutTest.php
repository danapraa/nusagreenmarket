<?php

namespace Tests\Feature\Customer;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper untuk membuat user customer siap belanja.
     */
    private function createCustomer()
    {
        return User::create([
            'name' => 'Buyer Test',
            'email' => 'buyer@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'phone' => '08123456789',
            'address' => 'Jl. Transaksi No. 88',
            'profile_completed' => true,
        ]);
    }

    /**
     * Helper produk
     */
    private function createProduct($stock = 10)
    {
        $category = Category::create(['name' => 'Sayur', 'slug' => 'sayur']);

        return Product::create([
            'name' => 'Wortel',
            'slug' => 'wortel-segar',
            'price' => 5000,
            'stock' => $stock,
            'category_id' => $category->id,
            'description' => 'Wortel sehat',
            'unit' => 'kg',
        ]);
    }

    #[Test]
    public function customer_cannot_access_checkout_page_if_cart_is_empty()
    {
        $user = $this->createCustomer();

        // Tidak isi keranjang, langsung akses checkout
        $response = $this->actingAs($user)->get(route('customer.checkout'));

        // Harusnya redirect balik ke keranjang dengan pesan error
        $response->assertStatus(302);
        $response->assertRedirect(route('customer.cart'));
    }

    #[Test]
    public function customer_can_access_checkout_page_with_items()
    {
        $user = $this->createCustomer();
        $product = $this->createProduct();

        // Isi Keranjang Manual
        $cart = Cart::create(['user_id' => $user->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 5000,
            'is_selected' => true, // PENTING: Harus true agar bisa checkout
        ]);

        $response = $this->actingAs($user)->get(route('customer.checkout'));

        $response->assertStatus(200);
        $response->assertSee('Checkout'); // Pastikan ada kata Checkout di view
    }

    #[Test]
    public function customer_can_process_checkout_successfully()
    {
        // 1. Setup Data
        $user = $this->createCustomer();
        $initialStock = 10;
        $buyQty = 3;
        $product = $this->createProduct($initialStock);

        // 2. Masukkan ke Keranjang
        $cart = Cart::create(['user_id' => $user->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => $buyQty,
            'price' => 5000,
            'is_selected' => true,
        ]);

        // 3. Data Form Checkout
        // PERBAIKAN DI SINI: Menyesuaikan nama field dengan error validasi tadi
        $checkoutData = [
            'shipping_address' => 'Jl. Pengiriman Baru No. 1', // Ganti 'address' jadi 'shipping_address'
            'phone' => '08999999999',
            'payment_method' => 'transfer',
            'delivery_date' => date('Y-m-d', strtotime('+1 day')), // Tambahkan tanggal pengiriman (besok)
        ];

        // 4. Lakukan POST Checkout
        $response = $this->actingAs($user)->post(route('customer.checkout.process'), $checkoutData);

        // === DEBUGGING (Bisa dihapus nanti kalau sudah PASS) ===
        if (session('error')) {
            dump("❌ PENYEBAB ERROR (Session 'error'):", session('error'));
        }

        if ($response->getSession()->get('errors')) {
            dump("❌ ERROR VALIDASI:", $response->getSession()->get('errors')->all());
        }
        // === DEBUGGING END ===

        // 5. Assertions (Pengecekan)

        // A. Pastikan Redirect ke halaman Detail Order (bukan error)
        $response->assertStatus(302);

        // B. Cek Tabel Orders: Harus ada 1 order baru
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'payment_method' => 'transfer',
            'status' => 'pending',
            'shipping_address' => 'Jl. Pengiriman Baru No. 1', // Pastikan alamat tersimpan
        ]);

        // C. Cek Tabel Order Items: Produk harus masuk
        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'quantity' => $buyQty,
            'product_name' => 'Wortel',
        ]);

        // D. Cek Tabel Products: Stok harus berkurang (10 - 3 = 7)
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => $initialStock - $buyQty,
        ]);

        // E. Cek Tabel Cart Items: Keranjang harus kosong (karena sudah dibeli)
        $this->assertDatabaseMissing('cart_items', [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
        ]);
    }
}
