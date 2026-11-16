<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_membuat_akun_pendaftar_lalu_bisa_login_lagi(): void
    {
        $this->post('/register', [
            'name'                  => 'Dummy Pendaftar',
            'email'                 => 'dummy@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('pendaftar.dashboard'));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'dummy@example.com',
            'role'  => 'pendaftar',
        ]);

        $this->post('/logout')->assertRedirect('/');
        $this->assertGuest();

        $this->post('/login', [
            'email'    => 'dummy@example.com',
            'password' => 'password123',
        ])->assertRedirect(route('pendaftar.dashboard'));

        $this->assertAuthenticated();
        $this->get('/pendaftar/dashboard')->assertOk();
    }

    public function test_login_gagal_jika_password_salah(): void
    {
        User::create([
            'name'     => 'Dummy Pendaftar',
            'email'    => 'dummy@example.com',
            'password' => 'password123',
            'role'     => 'pendaftar',
        ]);

        $this->from('/login')->post('/login', [
            'email'    => 'dummy@example.com',
            'password' => 'salahhhhh',
        ])->assertRedirect('/login')
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_password_disimpan_sekali_hash(): void
    {
        $this->post('/register', [
            'name'                  => 'Dummy Pendaftar',
            'email'                 => 'hash@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'hash@example.com')->firstOrFail();

        $this->assertTrue(Hash::check('password123', $user->getRawOriginal('password')));
    }
}
