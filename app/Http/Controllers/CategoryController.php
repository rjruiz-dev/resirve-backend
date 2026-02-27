<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * CategoryController
 * 
 * Maneja las operaciones relacionadas con categorías:
 * - Listar todas las categorías
 * - Mostrar detalles de una categoría específica
 * - Listar productos de una categoría
 */
class CategoryController extends Controller
{
    /**
     * Listar todas las categorías con contadores de productos
     * 
     * Retorna todas las categorías con la cantidad de productos
     * disponibles en cada una. Útil para el menú de filtros.
     * 
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $categories = Category::withCount([
            'products',
            'availableProducts'
        ])->get();

        return CategoryResource::collection($categories);
    }

    /**
     * Mostrar una categoría específica
     * 
     * Busca la categoría por su slug y retorna sus detalles
     * con la lista de productos disponibles en ella.
     * 
     * @param string $slug
     * @return CategoryResource
     */
    public function show(string $slug): CategoryResource
    {
        $category = Category::with(['availableProducts.primaryImage'])
            ->withCount(['products', 'availableProducts'])
            ->where('slug', $slug)
            ->firstOrFail();

        return new CategoryResource($category);
    }
}