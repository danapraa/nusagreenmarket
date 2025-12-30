<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_view_login_form()
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    #[Test]
    public function user_can_login_with_correct_credentials()
    {
        // 1. Kita buat user dengan status profile SUDAH LENGKAP
        // Supaya dia tidak dilempar ke /complete-profile
        $user = User::create([
            'name' => 'Naufal User',
            'email' => 'naufal@example.com',
            'password' => 'password123',
            'profile_completed' => true, // <--- KUNCI PERBAIKANNYA DI SINI
        ]);

        // 2. Coba login dengan data yang benar
        $response = $this->post(route('login'), [
            'email' => 'naufal@example.com',
            'password' => 'password123',
        ]);

        // 3. Sekarang harusnya berhasil masuk ke home
        $response->assertRedirect('/home');
        $this->assertAuthenticatedAs($user);
    }

    #[Test]
    public function user_cannot_login_with_incorrect_password()
    {
        User::create([
            'name' => 'Naufal User',
            'email' => 'naufal@example.com',
            'password' => 'password123',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'naufal@example.com',
            'password' => 'salah-password',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    #[Test]
    public function user_cannot_login_with_unregistered_email()
    {
        $response = $this->post(route('login'), [
            'email' => 'tidakada@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }
}
