<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        try {
            $query = Product::with('category');

            // Busca por nome, descrição ou categoria
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhereHas('category', function ($categoryQuery) use ($search) {
                          $categoryQuery->where('name', 'like', "%{$search}%");
                      });
                });
            }

            // Filtro por categoria
            if ($request->has('category_id')) {
                $query->where('category_id', $request->category_id);
            }

            // Filtro por preço
            if ($request->has('min_price')) {
                $query->where('price', '>=', $request->min_price);
            }
            if ($request->has('max_price')) {
                $query->where('price', '<=', $request->max_price);
            }

            // Filtro por estoque
            if ($request->has('in_stock') && $request->in_stock) {
                $query->where('stock_quantity', '>', 0);
            }

            // Ordenação
            $sortBy = $request->get('sort_by', 'name');
            $sortOrder = $request->get('sort_order', 'asc');
            $query->orderBy($sortBy, $sortOrder);

            // Paginação
            $perPage = min($request->get('per_page', 15), 100);
            $products = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => [
                    'products' => ProductResource::collection($products->items()),
                    'pagination' => [
                        'current_page' => $products->currentPage(),
                        'per_page' => $products->perPage(),
                        'total' => $products->total(),
                        'last_page' => $products->lastPage(),
                        'from' => $products->firstItem(),
                        'to' => $products->lastItem(),
                    ]
                ],
                'message' => 'Produtos obtidos com sucesso'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao obter produtos',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(ProductRequest $request)
    {
        try {
            $product = Product::create($request->validated());

            return response()->json([
                'success' => true,
                'data' => new ProductResource($product->load('category')),
                'message' => 'Produto criado com sucesso'
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar produto',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => new ProductResource($product->load('category')),
                'message' => 'Produto obtido com sucesso'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao obter produto',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified product in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        try {
            $product->update($request->validated());

            return response()->json([
                'success' => true,
                'data' => new ProductResource($product->load('category')),
                'message' => 'Produto atualizado com sucesso'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar produto',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        try {
            // Remover imagem se existir
            if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
                Storage::disk('public')->delete($product->image_path);
            }

            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Produto excluído com sucesso'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir produto',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Upload image for the specified product.
     */
    public function uploadImage(Request $request, Product $product)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Remover imagem anterior se existir
            if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
                Storage::disk('public')->delete($product->image_path);
            }

            // Salvar nova imagem
            $imagePath = $request->file('image')->store('products', 'public');
            
            $product->update(['image_path' => $imagePath]);

            return response()->json([
                'success' => true,
                'data' => [
                    'image_path' => $imagePath,
                    'image_url' => $product->image_url,
                    'product' => new ProductResource($product->load('category'))
                ],
                'message' => 'Imagem do produto atualizada com sucesso'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao fazer upload da imagem',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

