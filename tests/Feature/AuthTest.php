<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user registration
     */
    public function test_user_can_register()
    {
        $userData = [
            'name' => 'João Silva',
            'email' => 'joao@teste.com',
            'password' => '123456',
            'password_confirmation' => '123456',
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'user' => ['id', 'name', 'email'],
                        'access_token',
                        'token_type'
                    ],
                    'message'
                ]);

        $this->assertDatabaseHas('users', [
            'email' => 'joao@teste.com'
        ]);
    }

    /**
     * Test user login
     */
    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'teste@exemplo.com',
            'password' => bcrypt('123456'),
        ]);

        $loginData = [
            'email' => 'teste@exemplo.com',
            'password' => '123456',
        ];

        $response = $this->postJson('/api/auth/login', $loginData);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'user' => ['id', 'name', 'email'],
                        'access_token',
                        'token_type'
                    ],
                    'message'
                ]);
    }

    /**
     * Test login with invalid credentials
     */
    public function test_user_cannot_login_with_invalid_credentials()
    {
        $loginData = [
            'email' => 'inexistente@exemplo.com',
            'password' => 'senhaerrada',
        ];

        $response = $this->postJson('/api/auth/login', $loginData);

        $response->assertStatus(401)
                ->assertJson([
                    'success' => false,
                    'message' => 'Credenciais inválidas'
                ]);
    }

    /**
     * Test user logout
     */
    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/auth/logout');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Logout realizado com sucesso'
                ]);
    }

    /**
     * Test get authenticated user
     */
    public function test_can_get_authenticated_user()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/auth/user');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => ['id', 'name', 'email'],
                    'message'
                ]);
    }
}

