<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CustomerManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper untuk membuat Admin manual
     */
    private function createAdmin()
    {
        return User::create([
            'name' => 'Admin NusaGreenMarket',
            'email' => 'admin@nusagreen.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'phone' => '08123456700',
        ]);
    }

    /**
     * Helper untuk membuat Customer manual
     */
    private function createCustomer($name = 'Customer Biasa', $email = 'cust@test.com')
    {
        return User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt('password'),
            'role' => 'customer',
            'phone' => '08123456789',
            'address' => 'Jl. Kenangan No. 1',
            'profile_completed' => true,
        ]);
    }

    #[Test]
    public function admin_can_access_customer_list_page()
    {
        // 1. Setup Data
        $admin = $this->createAdmin();
        $customer = $this->createCustomer('Siti Nurbaya', 'siti@test.com');

        // 2. Login sebagai Admin & Akses Index
        $response = $this->actingAs($admin)->get(route('admin.customers.index'));

        // 3. Assertions
        $response->assertStatus(200);
        $response->assertSee('Daftar Customer'); // Pastikan teks ini ada di View Anda
        $response->assertSee('Siti Nurbaya'); // Pastikan nama customer muncul
        $response->assertSee('siti@test.com'); // Pastikan email muncul
    }

    #[Test]
    public function admin_can_view_specific_customer_detail()
    {
        // 1. Setup Data
        $admin = $this->createAdmin();
        $customer = $this->createCustomer('Budi Santoso', 'budi@test.com');

        // 2. Akses halaman detail customer
        $response = $this->actingAs($admin)->get(route('admin.customers.show', $customer));

        // 3. Assertions
        $response->assertStatus(200);
        $response->assertSee('Detail Customer'); // Sesuaikan dengan judul di view detail
        $response->assertSee('Budi Santoso');
        $response->assertSee('08123456789'); // Pastikan no hp muncul
        $response->assertSee('Jl. Kenangan No. 1'); // Pastikan alamat muncul
    }

    #[Test]
    public function admin_can_search_customer()
    {
        // 1. Setup Data
        $admin = $this->createAdmin();
        // Buat 2 customer berbeda
        $cust1 = $this->createCustomer('Andi Lau', 'andi@test.com');
        $cust2 = $this->createCustomer('Zaskia Gotik', 'zaskia@test.com');

        // 2. Search "Andi"
        $response = $this->actingAs($admin)->get(route('admin.customers.index', ['search' => 'Andi']));

        // 3. Assertions
        $response->assertStatus(200);
        $response->assertSee('Andi Lau'); // Andi harus muncul
        $response->assertDontSee('Zaskia Gotik'); // Zaskia JANGAN muncul
    }

    #[Test]
    public function non_admin_cannot_access_customer_management()
    {
        // 1. Setup Customer Jahil (bukan admin)
        $userJahil = $this->createCustomer('Hacker', 'hacker@test.com');

        // 2. Coba akses halaman admin customers
        $response = $this->actingAs($userJahil)->get(route('admin.customers.index'));

        // 3. Assertions: Harusnya Forbidden (403)
        $response->assertStatus(403);
    }
}
