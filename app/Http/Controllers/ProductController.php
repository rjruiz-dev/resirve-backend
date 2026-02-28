<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * ProductController
 * 
 * Maneja todas las operaciones relacionadas con productos:
 * - Listar productos con filtros y búsqueda
 * - Mostrar detalles de un producto específico
 * - Incrementar contador de vistas
 */
class ProductController extends Controller
{
    /**
     * Listar productos con filtros opcionales
     * 
     * Filtros disponibles:
     * - category: slug de la categoría (ej: ?category=electrodomesticos)
     * - status: disponible|reservado|vendido (ej: ?status=disponible)
     * - condition: como_nuevo|buen_estado|funcional
     * - price_min: precio mínimo (ej: ?price_min=10000)
     * - price_max: precio máximo (ej: ?price_max=100000)
     * - search: búsqueda por título o descripción (ej: ?search=bicicleta)
     * - featured: 1 para solo destacados (ej: ?featured=1)
     * - sort: created_at|price|views_count (ej: ?sort=price)
     * - order: asc|desc (ej: ?order=desc)
     * 
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        // Construir la query base
        $query = Product::with(['category', 'primaryImage']);

        // Filtro por categoría (usando el slug)
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filtro por status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        } else {
            // Por defecto solo mostrar disponibles en el catálogo público
            $query->where('status', 'disponible');
        }

        // Filtro por condición
        if ($request->has('condition')) {
            $query->where('condition', $request->condition);
        }

        // Filtro por rango de precio
        if ($request->has('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->has('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // Búsqueda por texto en título o descripción
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Filtro por destacados
        if ($request->has('featured') && $request->featured == 1) {
            $query->where('is_featured', true);
        }

        // Ordenamiento
        $sortField = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        
        // Validar que el campo de ordenamiento sea válido
        $allowedSortFields = ['created_at', 'price', 'views_count', 'title'];
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'created_at';
        }

        $query->orderBy($sortField, $sortOrder);

        // Ejecutar la query con paginación
        $products = $query->paginate(12);

        return ProductResource::collection($products);
    }

    /**
     * Mostrar los detalles de un producto específico
     * 
     * Busca el producto por su slug y devuelve toda la información
     * incluyendo categoría, todas las imágenes, y solicitudes de contacto.
     * También incrementa el contador de visitas.
     * 
     * @param string $slug
     * @return ProductResource
     */
    public function show(string $slug): ProductResource
    {
        $product = Product::with(['category', 'images'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Incrementar el contador de visitas
        $product->incrementViews();

        return new ProductResource($product);
    }

    /**
     * Obtener productos destacados para la página principal
     * 
     * Retorna hasta 4 productos marcados como destacados
     * y que estén disponibles.
     * 
     * @return AnonymousResourceCollection
     */
    public function featured(): AnonymousResourceCollection
    {
        $products = Product::with(['category', 'primaryImage'])
            ->where('is_featured', true)
            ->where('status', 'disponible')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        return ProductResource::collection($products);
    }

    /**
     * Obtener productos recientes para la página principal
     * 
     * Retorna los últimos 6 productos agregados que estén disponibles.
     * 
     * @return AnonymousResourceCollection
     */
    public function recent(): AnonymousResourceCollection
    {
        $products = Product::with(['category', 'primaryImage'])
            ->where('status', 'disponible')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        return ProductResource::collection($products);
    }

    /**
     * Obtener productos relacionados
     * 
     * Busca productos de la misma categoría, excluyendo el producto actual.
     * Útil para mostrar "También te puede interesar" en la ficha del producto.
     * 
     * @param string $slug
     * @return AnonymousResourceCollection
     */
    public function related(string $slug): AnonymousResourceCollection
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        $relatedProducts = Product::with(['category', 'primaryImage'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'disponible')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        return ProductResource::collection($relatedProducts);
    }

    /**
     * Obtener estadísticas básicas del catálogo
     * 
     * Retorna contadores útiles para mostrar en el frontend.
     * 
     * @return JsonResponse
     */
    public function stats(): JsonResponse
    {
        return response()->json([
            'total_products' => Product::count(),
            'available_products' => Product::where('status', 'disponible')->count(),
            'reserved_products' => Product::where('status', 'reservado')->count(),
            'sold_products' => Product::where('status', 'vendido')->count(),
            'categories_count' => Product::distinct('category_id')->count(),
        ]);
    }
}
