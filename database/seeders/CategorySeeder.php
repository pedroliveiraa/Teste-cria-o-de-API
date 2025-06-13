<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Eletrônicos',
                'description' => 'Produtos eletrônicos como smartphones, tablets, notebooks e acessórios'
            ],
            [
                'name' => 'Roupas',
                'description' => 'Vestuário masculino e feminino, calçados e acessórios de moda'
            ],
            [
                'name' => 'Casa e Jardim',
                'description' => 'Produtos para casa, decoração, móveis e jardinagem'
            ],
            [
                'name' => 'Esportes',
                'description' => 'Equipamentos esportivos, roupas fitness e suplementos'
            ],
            [
                'name' => 'Livros',
                'description' => 'Livros físicos e digitais de diversos gêneros'
            ],
            [
                'name' => 'Beleza',
                'description' => 'Produtos de beleza, cosméticos e cuidados pessoais'
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}

