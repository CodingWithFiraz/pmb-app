<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthRoleTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => \Database\Seeders\UserSeeder::class]);
    }

    public function test_guest_access_admin_redirects_to_login(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/login');
    }

    public function test_login_admin_redirects_to_admin_dashboard(): void
    {
        $response = $this->post('/login', [
            'email'    => 'admin@pmb.ac.id',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($this->app->make(\App\Models\User::class)->where('email', 'admin@pmb.ac.id')->first());
    }

    public function test_login_pendaftar_redirects_to_pendaftar_dashboard(): void
    {
        $response = $this->post('/login', [
            'email'    => 'pendaftar@pmb.ac.id',
            'password' => 'pendaftar123',
        ]);

        $response->assertRedirect('/pendaftar/dashboard');
        $this->assertAuthenticated();
    }

    public function test_admin_dashboard_is_accessible_by_admin(): void
    {
        $this->post('/login', [
            'email'    => 'admin@pmb.ac.id',
            'password' => 'admin123',
        ]);

        $response = $this->get('/admin/dashboard');

        $response->assertStatus(200);
    }

    public function test_pendaftar_dashboard_is_accessible_by_pendaftar(): void
    {
        $this->post('/login', [
            'email'    => 'pendaftar@pmb.ac.id',
            'password' => 'pendaftar123',
        ]);

        $response = $this->get('/pendaftar/dashboard');

        $response->assertStatus(200);
    }

    public function test_pendaftar_cannot_access_admin_dashboard(): void
    {
        $this->post('/login', [
            'email'    => 'pendaftar@pmb.ac.id',
            'password' => 'pendaftar123',
        ]);

        $response = $this->get('/admin/dashboard');

        $response->assertStatus(403);
    }
}
