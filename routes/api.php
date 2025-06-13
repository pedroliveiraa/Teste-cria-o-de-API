<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Rotas públicas de autenticação
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Rotas protegidas por autenticação
Route::middleware('auth:sanctum')->group(function () {
    
    // Rotas de autenticação protegidas
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
    });

    // Rotas de categorias
    Route::apiResource('categories', CategoryController::class);

    // Rotas de produtos
    Route::apiResource('products', ProductController::class);
    Route::post('products/{product}/image', [ProductController::class, 'uploadImage']);
});

// Rota de teste da API
Route::get('/', function () {
    return response()->json([
        'success' => true,
        'message' => 'Proxxy Products API está funcionando!',
        'version' => '1.0',
        'endpoints' => [
            'auth' => [
                'POST /api/auth/register' => 'Registrar usuário',
                'POST /api/auth/login' => 'Fazer login',
                'POST /api/auth/logout' => 'Fazer logout (requer token)',
                'GET /api/auth/user' => 'Obter dados do usuário (requer token)',
            ],
            'categories' => [
                'GET /api/categories' => 'Listar categorias (requer token)',
                'POST /api/categories' => 'Criar categoria (requer token)',
                'GET /api/categories/{id}' => 'Obter categoria específica (requer token)',
                'PUT /api/categories/{id}' => 'Atualizar categoria (requer token)',
                'DELETE /api/categories/{id}' => 'Excluir categoria (requer token)',
            ],
            'products' => [
                'GET /api/products' => 'Listar produtos (requer token)',
                'POST /api/products' => 'Criar produto (requer token)',
                'GET /api/products/{id}' => 'Obter produto específico (requer token)',
                'PUT /api/products/{id}' => 'Atualizar produto (requer token)',
                'DELETE /api/products/{id}' => 'Excluir produto (requer token)',
                'POST /api/products/{id}/image' => 'Upload de imagem do produto (requer token)',
            ]
        ]
    ]);
});

