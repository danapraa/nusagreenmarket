<?php

namespace Tests\Feature\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class OrderManagementTest extends TestCase
{
    use RefreshDatabase;

    // --- HELPERS ---

    private function createAdmin()
    {
        return User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'phone' => '08123456700',
        ]);
    }

    private function createCustomer()
    {
        return User::create([
            'name' => 'Customer Test',
            'email' => 'cust@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'phone' => '08123456789',
            'address' => 'Jl. Penerima No. 1',
            'profile_completed' => true,
        ]);
    }

    private function createOrder($customer)
    {
        // 1. Buat Produk Dummy
        $category = Category::create(['name' => 'Sayur', 'slug' => 'sayur']);
        $product = Product::create([
            'name' => 'Bayam',
            'slug' => 'bayam',
            'price' => 5000,
            'stock' => 10,
            'category_id' => $category->id,
            'description' => 'Bayam Segar',
            'unit' => 'ikat',
        ]);

        // 2. Buat Order
        $order = Order::create([
            'user_id' => $customer->id,
            'order_number' => 'ORD-12345',
            'subtotal' => 10000,
            'shipping_cost' => 5000,
            'total' => 15000,
            'status' => 'pending',       // Status awal
            'payment_status' => 'unpaid',
            'payment_method' => 'transfer',
            'shipping_address' => 'Jl. Penerima No. 1',
            'phone' => '08123456789',
            'delivery_date' => now()->addDay(),
        ]);

        // 3. Buat Order Item
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => 2,
            'price' => 5000,
            'subtotal' => 10000,
        ]);

        return $order;
    }

    // --- TESTS ---

    #[Test]
    public function admin_can_view_order_list()
    {
        $admin = $this->createAdmin();
        $customer = $this->createCustomer();
        $order = $this->createOrder($customer);

        $response = $this->actingAs($admin)->get(route('admin.orders.index'));

        $response->assertStatus(200);
        $response->assertSee('ORD-12345'); // Cek No Order
        $response->assertSee('Customer Test'); // Cek Nama Customer
        $response->assertSee('Pending'); // Cek Status
    }

    #[Test]
    public function admin_can_view_order_details()
    {
        $admin = $this->createAdmin();
        $customer = $this->createCustomer();
        $order = $this->createOrder($customer);

        $response = $this->actingAs($admin)->get(route('admin.orders.show', $order));

        $response->assertStatus(200);
        $response->assertSee('Bayam'); // Produk harus muncul
        $response->assertSee('Jl. Penerima No. 1'); // Alamat harus muncul
        $response->assertSee('Rp15.000'); // Total harga harus muncul (format view Anda mungkin beda, sesuaikan string ini)
    }

    #[Test]
    public function admin_can_update_status_and_input_resi()
    {
        $admin = $this->createAdmin();
        $customer = $this->createCustomer();
        $order = $this->createOrder($customer);

        // Data update: Ubah status jadi 'shipped' dan masukkan Resi
        $updateData = [
            'status' => 'shipped',
            'tracking_number' => 'JNE-88889999',
            'courier_info' => 'JNE Reguler',
            'notes' => 'Barang sudah dikirim ya',
        ];

        $response = $this->actingAs($admin)->patch(route('admin.orders.update-status', $order), $updateData);

        // Assert: Redirect kembali
        $response->assertStatus(302);

        // Assert DB: Data terupdate
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'shipped',
            'tracking_number' => 'JNE-88889999',
            'courier_info' => 'JNE Reguler',
        ]);
    }

    #[Test]
    public function admin_can_verify_payment()
    {
        $admin = $this->createAdmin();
        $customer = $this->createCustomer();
        $order = $this->createOrder($customer);

        // Simulasi: Order sudah ada bukti bayar tapi status masih unpaid
        $order->update(['payment_proof' => 'dummy.jpg']);

        // Action: Admin konfirmasi pembayaran
        $response = $this->actingAs($admin)->patch(route('admin.orders.update-payment', $order), [
            'payment_status' => 'paid'
        ]);

        $response->assertStatus(302);

        // Assert DB: Status berubah jadi paid
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => 'paid',
        ]);
    }

    #[Test]
    public function customer_cannot_access_admin_order_management()
    {
        $customer = $this->createCustomer();

        // Customer coba akses halaman admin order
        $response = $this->actingAs($customer)->get(route('admin.orders.index'));

        // Harus ditolak
        $response->assertStatus(403);
    }
}
