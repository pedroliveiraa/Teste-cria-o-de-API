<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index(Request $request)
    {
        try {
            $query = Category::query();

            // Busca por nome ou descrição
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Ordenação
            $sortBy = $request->get('sort_by', 'name');
            $sortOrder = $request->get('sort_order', 'asc');
            $query->orderBy($sortBy, $sortOrder);

            // Paginação
            $perPage = min($request->get('per_page', 15), 100);
            $categories = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => [
                    'categories' => CategoryResource::collection($categories->items()),
                    'pagination' => [
                        'current_page' => $categories->currentPage(),
                        'per_page' => $categories->perPage(),
                        'total' => $categories->total(),
                        'last_page' => $categories->lastPage(),
                        'from' => $categories->firstItem(),
                        'to' => $categories->lastItem(),
                    ]
                ],
                'message' => 'Categorias obtidas com sucesso'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao obter categorias',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(CategoryRequest $request)
    {
        try {
            $category = Category::create($request->validated());

            return response()->json([
                'success' => true,
                'data' => new CategoryResource($category),
                'message' => 'Categoria criada com sucesso'
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar categoria',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => new CategoryResource($category->load('products')),
                'message' => 'Categoria obtida com sucesso'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao obter categoria',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified category in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        try {
            $category->update($request->validated());

            return response()->json([
                'success' => true,
                'data' => new CategoryResource($category),
                'message' => 'Categoria atualizada com sucesso'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar categoria',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category)
    {
        try {
            // Verificar se há produtos associados
            if ($category->products()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Não é possível excluir categoria que possui produtos associados'
                ], Response::HTTP_CONFLICT);
            }

            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Categoria excluída com sucesso'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir categoria',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

