<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Eletrônicos
            [
                'name' => 'iPhone 15 Pro',
                'description' => 'Smartphone Apple iPhone 15 Pro com 128GB de armazenamento, câmera tripla e chip A17 Pro',
                'price' => 7999.99,
                'stock_quantity' => 25,
                'category_name' => 'Eletrônicos'
            ],
            [
                'name' => 'Samsung Galaxy S24',
                'description' => 'Smartphone Samsung Galaxy S24 com 256GB, tela AMOLED 6.2" e câmera de 50MP',
                'price' => 4999.99,
                'stock_quantity' => 30,
                'category_name' => 'Eletrônicos'
            ],
            [
                'name' => 'MacBook Air M2',
                'description' => 'Notebook Apple MacBook Air com chip M2, 8GB RAM, 256GB SSD e tela Retina 13.6"',
                'price' => 12999.99,
                'stock_quantity' => 15,
                'category_name' => 'Eletrônicos'
            ],

            // Roupas
            [
                'name' => 'Camiseta Básica Algodão',
                'description' => 'Camiseta básica 100% algodão, disponível em várias cores e tamanhos',
                'price' => 39.99,
                'stock_quantity' => 100,
                'category_name' => 'Roupas'
            ],
            [
                'name' => 'Calça Jeans Skinny',
                'description' => 'Calça jeans skinny feminina, cintura alta, tecido stretch confortável',
                'price' => 129.99,
                'stock_quantity' => 50,
                'category_name' => 'Roupas'
            ],

            // Casa e Jardim
            [
                'name' => 'Sofá 3 Lugares',
                'description' => 'Sofá de 3 lugares em tecido suede, estrutura de madeira maciça, cor cinza',
                'price' => 1299.99,
                'stock_quantity' => 8,
                'category_name' => 'Casa e Jardim'
            ],
            [
                'name' => 'Conjunto de Panelas Antiaderente',
                'description' => 'Conjunto com 5 panelas antiaderente, cabos de baquelite e tampas de vidro',
                'price' => 299.99,
                'stock_quantity' => 20,
                'category_name' => 'Casa e Jardim'
            ],

            // Esportes
            [
                'name' => 'Tênis de Corrida Nike',
                'description' => 'Tênis Nike Air Zoom para corrida, tecnologia de amortecimento e respirabilidade',
                'price' => 499.99,
                'stock_quantity' => 35,
                'category_name' => 'Esportes'
            ],
            [
                'name' => 'Bicicleta Mountain Bike',
                'description' => 'Bicicleta mountain bike aro 29, 21 marchas, freios a disco e suspensão dianteira',
                'price' => 1899.99,
                'stock_quantity' => 12,
                'category_name' => 'Esportes'
            ],

            // Livros
            [
                'name' => 'Clean Code - Código Limpo',
                'description' => 'Livro sobre boas práticas de programação por Robert C. Martin',
                'price' => 89.99,
                'stock_quantity' => 40,
                'category_name' => 'Livros'
            ],
            [
                'name' => 'O Hobbit',
                'description' => 'Clássico da literatura fantástica por J.R.R. Tolkien, edição especial',
                'price' => 45.99,
                'stock_quantity' => 60,
                'category_name' => 'Livros'
            ],

            // Beleza
            [
                'name' => 'Base Líquida Matte',
                'description' => 'Base líquida com acabamento matte, cobertura média a alta, 12 horas de duração',
                'price' => 79.99,
                'stock_quantity' => 75,
                'category_name' => 'Beleza'
            ],
            [
                'name' => 'Kit Cuidados com a Pele',
                'description' => 'Kit completo com limpador facial, tônico, sérum e hidratante para pele mista',
                'price' => 199.99,
                'stock_quantity' => 30,
                'category_name' => 'Beleza'
            ]
        ];

        foreach ($products as $productData) {
            $category = Category::where('name', $productData['category_name'])->first();
            
            if ($category) {
                Product::create([
                    'name' => $productData['name'],
                    'description' => $productData['description'],
                    'price' => $productData['price'],
                    'stock_quantity' => $productData['stock_quantity'],
                    'category_id' => $category->id,
                ]);
            }
        }
    }
}

