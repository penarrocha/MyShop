<?php

namespace Tests\Feature\Auth;

use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $this->get('/register')->assertOk();
    }

    public function test_new_users_can_register(): void
    {
        Config::set('services.recaptcha.min_score', 0.1);
        Config::set('services.recaptcha.secret', 'testing-secret');

        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => true,
                'score'   => 0.9,
                'action'  => 'register',
            ], 200),
        ]);

        Role::firstOrCreate(['name' => 'customer', 'slug' => 'customer']);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'recaptcha_token' => 'fake-token',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect();
    }
}
