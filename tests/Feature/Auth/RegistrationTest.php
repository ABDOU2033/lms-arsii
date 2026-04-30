<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register_and_attendre_activation(): void
    {
        $response = $this->post('/register', [
            'nom' => 'Test User',
            'email' => 'test@example.com',
            'mot_de_passe' => 'password',
            'mot_de_passe_confirmation' => 'password',
            'role' => 'etudiant',
        ]);

        $this->assertGuest();
        $response->assertRedirect('/login');
    }
}
