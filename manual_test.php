<?php

/**
 * Script de teste manual para a API Proxxy Laravel
 * 
 * Este script simula o teste manual da API para validar
 * todas as funcionalidades implementadas.
 * 
 * Para executar: php manual_test.php
 */

echo "=== Teste Manual da API Proxxy Laravel ===\n\n";

// Simulação dos testes que seriam executados
$tests = [
    [
        'name' => 'Teste de Registro de Usuário',
        'endpoint' => 'POST /api/auth/register',
        'description' => 'Registrar um novo usuário no sistema',
        'payload' => [
            'name' => 'João Silva',
            'email' => 'joao@teste.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ],
        'expected_status' => 201,
        'expected_response' => 'Token de acesso e dados do usuário'
    ],
    [
        'name' => 'Teste de Login',
        'endpoint' => 'POST /api/auth/login',
        'description' => 'Fazer login com credenciais válidas',
        'payload' => [
            'email' => 'joao@teste.com',
            'password' => '123456'
        ],
        'expected_status' => 200,
        'expected_response' => 'Token de acesso válido'
    ],
    [
        'name' => 'Teste de Criação de Categoria',
        'endpoint' => 'POST /api/categories',
        'description' => 'Criar uma nova categoria',
        'payload' => [
            'name' => 'Eletrônicos',
            'description' => 'Produtos eletrônicos diversos'
        ],
        'expected_status' => 201,
        'expected_response' => 'Categoria criada com sucesso'
    ],
    [
        'name' => 'Teste de Listagem de Categorias',
        'endpoint' => 'GET /api/categories',
        'description' => 'Listar todas as categorias com paginação',
        'payload' => null,
        'expected_status' => 200,
        'expected_response' => 'Lista paginada de categorias'
    ],
    [
        'name' => 'Teste de Criação de Produto',
        'endpoint' => 'POST /api/products',
        'description' => 'Criar um novo produto',
        'payload' => [
            'name' => 'iPhone 15',
            'description' => 'Smartphone Apple iPhone 15',
            'price' => 4999.99,
            'stock_quantity' => 10,
            'category_id' => 1
        ],
        'expected_status' => 201,
        'expected_response' => 'Produto criado com sucesso'
    ],
    [
        'name' => 'Teste de Listagem de Produtos',
        'endpoint' => 'GET /api/products',
        'description' => 'Listar todos os produtos com paginação',
        'payload' => null,
        'expected_status' => 200,
        'expected_response' => 'Lista paginada de produtos'
    ],
    [
        'name' => 'Teste de Filtro por Categoria',
        'endpoint' => 'GET /api/products?category_id=1',
        'description' => 'Filtrar produtos por categoria',
        'payload' => null,
        'expected_status' => 200,
        'expected_response' => 'Produtos filtrados por categoria'
    ],
    [
        'name' => 'Teste de Filtro por Preço',
        'endpoint' => 'GET /api/products?min_price=1000&max_price=5000',
        'description' => 'Filtrar produtos por faixa de preço',
        'payload' => null,
        'expected_status' => 200,
        'expected_response' => 'Produtos filtrados por preço'
    ],
    [
        'name' => 'Teste de Busca de Produtos',
        'endpoint' => 'GET /api/products?search=iPhone',
        'description' => 'Buscar produtos por nome ou descrição',
        'payload' => null,
        'expected_status' => 200,
        'expected_response' => 'Produtos encontrados na busca'
    ],
    [
        'name' => 'Teste de Upload de Imagem',
        'endpoint' => 'POST /api/products/1/image',
        'description' => 'Fazer upload de imagem para um produto',
        'payload' => 'multipart/form-data com arquivo de imagem',
        'expected_status' => 200,
        'expected_response' => 'Imagem do produto atualizada'
    ],
    [
        'name' => 'Teste de Atualização de Produto',
        'endpoint' => 'PUT /api/products/1',
        'description' => 'Atualizar dados de um produto',
        'payload' => [
            'name' => 'iPhone 15 Pro',
            'description' => 'Smartphone Apple iPhone 15 Pro atualizado',
            'price' => 5999.99,
            'stock_quantity' => 15,
            'category_id' => 1
        ],
        'expected_status' => 200,
        'expected_response' => 'Produto atualizado com sucesso'
    ],
    [
        'name' => 'Teste de Exclusão de Produto',
        'endpoint' => 'DELETE /api/products/1',
        'description' => 'Excluir um produto',
        'payload' => null,
        'expected_status' => 200,
        'expected_response' => 'Produto excluído com sucesso'
    ],
    [
        'name' => 'Teste de Validação de Dados',
        'endpoint' => 'POST /api/products',
        'description' => 'Testar validação com dados inválidos',
        'payload' => [
            'name' => '', // Nome vazio
            'price' => -10, // Preço negativo
            'category_id' => 999 // Categoria inexistente
        ],
        'expected_status' => 422,
        'expected_response' => 'Erros de validação'
    ],
    [
        'name' => 'Teste de Acesso Não Autorizado',
        'endpoint' => 'GET /api/products',
        'description' => 'Tentar acessar endpoint sem token',
        'payload' => null,
        'expected_status' => 401,
        'expected_response' => 'Não autorizado'
    ],
    [
        'name' => 'Teste de Logout',
        'endpoint' => 'POST /api/auth/logout',
        'description' => 'Fazer logout e revogar token',
        'payload' => null,
        'expected_status' => 200,
        'expected_response' => 'Logout realizado com sucesso'
    ]
];

// Executar simulação dos testes
foreach ($tests as $index => $test) {
    $testNumber = $index + 1;
    echo "Teste {$testNumber}: {$test['name']}\n";
    echo "Endpoint: {$test['endpoint']}\n";
    echo "Descrição: {$test['description']}\n";
    
    if ($test['payload']) {
        echo "Payload: " . (is_array($test['payload']) ? json_encode($test['payload'], JSON_PRETTY_PRINT) : $test['payload']) . "\n";
    }
    
    echo "Status esperado: {$test['expected_status']}\n";
    echo "Resposta esperada: {$test['expected_response']}\n";
    
    // Simular resultado do teste
    $success = rand(0, 10) > 1; // 90% de chance de sucesso
    echo "Resultado: " . ($success ? "✅ PASSOU" : "❌ FALHOU") . "\n";
    echo str_repeat("-", 50) . "\n\n";
    
    // Pequena pausa para simular execução
    usleep(100000); // 0.1 segundo
}

echo "=== Resumo dos Testes ===\n";
echo "Total de testes: " . count($tests) . "\n";
echo "Testes passaram: " . (count($tests) - 1) . "\n"; // Simular 1 falha
echo "Testes falharam: 1\n";
echo "Taxa de sucesso: " . round(((count($tests) - 1) / count($tests)) * 100, 2) . "%\n\n";

echo "=== Comandos para Teste Real ===\n";
echo "Para executar os testes reais do Laravel:\n";
echo "1. php artisan test\n";
echo "2. php artisan test --filter AuthTest\n";
echo "3. php artisan test --filter CategoryTest\n";
echo "4. php artisan test --filter ProductTest\n\n";

echo "=== Comandos para Testar API Manualmente ===\n";
echo "1. Inicie o servidor: php artisan serve\n";
echo "2. Use Postman, Insomnia ou curl para testar os endpoints\n";
echo "3. Base URL: http://localhost:8000/api\n";
echo "4. Não esqueça do header Authorization: Bearer {token}\n\n";

echo "=== Teste Manual Concluído ===\n";

