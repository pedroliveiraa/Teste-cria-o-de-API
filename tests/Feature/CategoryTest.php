<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    /**
     * Test category creation
     */
    public function test_can_create_category()
    {
        $categoryData = [
            'name' => 'Eletrônicos',
            'description' => 'Produtos eletrônicos diversos'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/categories', $categoryData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'data' => ['id', 'name', 'description'],
                    'message'
                ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Eletrônicos'
        ]);
    }

    /**
     * Test category listing
     */
    public function test_can_list_categories()
    {
        Category::factory()->count(3)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/categories');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'categories' => [
                            '*' => ['id', 'name', 'description']
                        ],
                        'pagination'
                    ],
                    'message'
                ]);
    }

    /**
     * Test category update
     */
    public function test_can_update_category()
    {
        $category = Category::factory()->create();

        $updateData = [
            'name' => 'Nome Atualizado',
            'description' => 'Descrição atualizada'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson("/api/categories/{$category->id}", $updateData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Nome Atualizado'
        ]);
    }

    /**
     * Test category deletion
     */
    public function test_can_delete_category()
    {
        $category = Category::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id
        ]);
    }

    /**
     * Test unauthorized access
     */
    public function test_cannot_access_without_token()
    {
        $response = $this->getJson('/api/categories');

        $response->assertStatus(401);
    }
}

