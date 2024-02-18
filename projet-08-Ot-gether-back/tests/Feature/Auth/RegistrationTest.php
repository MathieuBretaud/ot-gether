<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/api/register', [
            'pseudo' => 'toto',
            'lastname' => 'john',
            'firstname' => 'DO',
            'city' => 'Grenobble',
            'email' => 'john@doe.fr',
            'password' => 'password',
            'picture' => 'onsenfou.jpg',
            'is_admin' => false,
            // 'password' => 'password',
            // 'password_confirmation' => 'password',
        ]);

        // $this->assertAuthenticated();
        $response->assertStatus(200);
    }
}
