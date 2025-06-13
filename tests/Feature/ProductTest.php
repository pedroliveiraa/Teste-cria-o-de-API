<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;
        $this->category = Category::factory()->create();
    }

    /**
     * Test product creation
     */
    public function test_can_create_product()
    {
        $productData = [
            'name' => 'iPhone 15',
            'description' => 'Smartphone Apple iPhone 15',
            'price' => 4999.99,
            'stock_quantity' => 10,
            'category_id' => $this->category->id
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/products', $productData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'data' => ['id', 'name', 'description', 'price', 'stock_quantity'],
                    'message'
                ]);

        $this->assertDatabaseHas('products', [
            'name' => 'iPhone 15'
        ]);
    }

    /**
     * Test product listing
     */
    public function test_can_list_products()
    {
        Product::factory()->count(3)->create([
            'category_id' => $this->category->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/products');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'products' => [
                            '*' => ['id', 'name', 'description', 'price', 'stock_quantity']
                        ],
                        'pagination'
                    ],
                    'message'
                ]);
    }

    /**
     * Test product filtering by category
     */
    public function test_can_filter_products_by_category()
    {
        $otherCategory = Category::factory()->create();
        
        Product::factory()->create(['category_id' => $this->category->id]);
        Product::factory()->create(['category_id' => $otherCategory->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/products?category_id={$this->category->id}");

        $response->assertStatus(200);
        
        $products = $response->json('data.products');
        $this->assertCount(1, $products);
        $this->assertEquals($this->category->id, $products[0]['category']['id']);
    }

    /**
     * Test product price filtering
     */
    public function test_can_filter_products_by_price()
    {
        Product::factory()->create([
            'category_id' => $this->category->id,
            'price' => 100.00
        ]);
        Product::factory()->create([
            'category_id' => $this->category->id,
            'price' => 500.00
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/products?min_price=200&max_price=600');

        $response->assertStatus(200);
        
        $products = $response->json('data.products');
        $this->assertCount(1, $products);
        $this->assertEquals(500.00, $products[0]['price']);
    }

    /**
     * Test product update
     */
    public function test_can_update_product()
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id
        ]);

        $updateData = [
            'name' => 'Nome Atualizado',
            'description' => 'Descrição atualizada',
            'price' => 999.99,
            'stock_quantity' => 20,
            'category_id' => $this->category->id
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson("/api/products/{$product->id}", $updateData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Nome Atualizado',
            'price' => 999.99
        ]);
    }

    /**
     * Test product deletion
     */
    public function test_can_delete_product()
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('products', [
            'id' => $product->id
        ]);
    }

    /**
     * Test product validation
     */
    public function test_product_validation_fails_with_invalid_data()
    {
        $invalidData = [
            'name' => '', // Nome vazio
            'price' => -10, // Preço negativo
            'stock_quantity' => -5, // Estoque negativo
            'category_id' => 999 // Categoria inexistente
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/products', $invalidData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name', 'description', 'price', 'stock_quantity', 'category_id']);
    }
}

