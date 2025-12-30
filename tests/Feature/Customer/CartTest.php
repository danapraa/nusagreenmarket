<?php

namespace Tests\Feature\Customer;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test; // Tambahkan ini untuk menghilangkan warning @test

class CartTest extends TestCase
{
    use RefreshDatabase;

    #[Test] // Ganti @test dengan atribut ini (opsional, biar warning hilang)
    public function customer_can_access_cart_page()
    {
        $user = User::create([
            'name' => 'Customer Test',
            'email' => 'customer@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'phone' => '08123456789',
            'address' => 'Jl. Test No. 1',
            'profile_completed' => true,
        ]);

        $response = $this->actingAs($user)->get(route('customer.cart'));
        $response->assertStatus(200);
    }

    #[Test]
    public function customer_can_add_product_to_cart()
    {
        $user = User::create([
            'name' => 'Customer Test',
            'email' => 'customer@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'phone' => '08123456789',
            'address' => 'Jl. Test',
            'profile_completed' => true,
        ]);

        $category = Category::create(['name' => 'Sayuran', 'slug' => 'sayuran']);

        $product = Product::create([
            'name' => 'Bayam Segar',
            'slug' => 'bayam-segar',
            'price' => 5000,
            'stock' => 10,
            'category_id' => $category->id,
            'description' => 'Bayam enak', // Ini ada, makanya PASS
            'unit' => 'ikat',
        ]);

        $response = $this->actingAs($user)->post(route('customer.cart.add', $product), [
            'quantity' => 2
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
    }

    #[Test]
    public function customer_can_toggle_checkbox_selection()
    {
        // 1. Buat User
        $user = User::create([
            'name' => 'Customer Checkbox',
            'email' => 'checkbox@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'phone' => '08123456789',
            'address' => 'Jl. Test',
            'profile_completed' => true,
        ]);

        // 2. Buat Produk
        $category = Category::create(['name' => 'Buah', 'slug' => 'buah']);

        $product = Product::create([
            'name' => 'Apel',
            'slug' => 'apel',
            'price' => 10000,
            'stock' => 20,
            'category_id' => $category->id,
            'description' => 'Apel manis segar', // <--- PERBAIKAN: Tambahkan baris ini
            'unit' => 'kg', // Tambahkan juga unit biar aman
        ]);

        // 3. Buat Cart & Item Manual
        $cart = Cart::create(['user_id' => $user->id]);

        $cartItem = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 10000,
            'is_selected' => true,
        ]);

        // 4. Lakukan Toggle
        $response = $this->actingAs($user)->patch(route('customer.cart.toggle', $cartItem));

        // 5. Cek DB
        $this->assertDatabaseHas('cart_items', [
            'id' => $cartItem->id,
            'is_selected' => false,
        ]);
    }
}
