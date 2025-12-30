<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    // --- HELPERS ---

    private function createAdmin()
    {
        return User::create([
            'name' => 'Admin Kategori',
            'email' => 'admin.cat@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'phone' => '08123456000',
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

    // --- TESTS ---

    #[Test]
    public function admin_can_access_category_list_page()
    {
        $admin = $this->createAdmin();

        // Buat data dummy kategori
        Category::create(['name' => 'Sayur Mayur', 'slug' => 'sayur-mayur', 'icon' => '🥬']);
        Category::create(['name' => 'Buah Segar', 'slug' => 'buah-segar', 'icon' => '🍎']);

        $response = $this->actingAs($admin)->get(route('admin.categories.index'));

        $response->assertStatus(200);
        $response->assertSee('Sayur Mayur');
        $response->assertSee('Buah Segar');
    }

    #[Test]
    public function admin_can_create_new_category()
    {
        $admin = $this->createAdmin();

        // Data input form
        // PERBAIKAN: Icon dikirim sebagai string (emoji/text), bukan file upload
        $data = [
            'name' => 'Rempah Rempah',
            'icon' => '🌶️',
        ];

        // Action: Post ke route store
        $response = $this->actingAs($admin)->post(route('admin.categories.store'), $data);

        // Assert: Redirect ke index
        $response->assertStatus(302);
        $response->assertRedirect(route('admin.categories.index'));

        // Assert DB: Data masuk
        $this->assertDatabaseHas('categories', [
            'name' => 'Rempah Rempah',
            'icon' => '🌶️',
        ]);
    }

    #[Test]
    public function admin_can_update_category()
    {
        $admin = $this->createAdmin();

        // 1. Buat kategori awal
        $category = Category::create([
            'name' => 'Sayuran Lama',
            'slug' => 'sayuran-lama',
            'icon' => 'old_icon'
        ]);

        // 2. Data update
        $updateData = [
            'name' => 'Sayuran Baru Update',
            'icon' => '🥦', // Update icon jadi brokoli
        ];

        // 3. Action: Put ke route update
        $response = $this->actingAs($admin)->put(route('admin.categories.update', $category), $updateData);

        // 4. Assert
        $response->assertStatus(302);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Sayuran Baru Update',
            'icon' => '🥦',
        ]);
    }

    #[Test]
    public function admin_can_delete_category()
    {
        $admin = $this->createAdmin();

        // 1. Buat kategori
        $category = Category::create(['name' => 'Kategori Hapus', 'slug' => 'kategori-hapus', 'icon' => 'x']);

        // 2. Action: Delete
        $response = $this->actingAs($admin)->delete(route('admin.categories.destroy', $category));

        // 3. Assert
        $response->assertStatus(302);

        // Pastikan data hilang dari DB
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    #[Test]
    public function customer_cannot_manage_categories()
    {
        $customer = $this->createCustomer();

        // Coba akses halaman index kategori
        $response = $this->actingAs($customer)->get(route('admin.categories.index'));

        // Harusnya ditolak (403 Forbidden)
        $response->assertStatus(403);
    }
}
