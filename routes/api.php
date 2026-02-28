<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí se definen todas las rutas de la API REST de ReSirve.
| Todas estas rutas tienen el prefijo /api automáticamente.
| Por ejemplo: /api/products, /api/categories, etc.
|
*/

/*
|--------------------------------------------------------------------------
| Rutas Públicas - No requieren autenticación
|--------------------------------------------------------------------------
*/

// PRODUCTOS
// Estas rutas están ordenadas de más específica a menos específica
// para evitar conflictos de routing

// Endpoints especiales de productos (deben ir ANTES de {slug})
Route::get('/products/featured', [ProductController::class, 'featured'])
    ->name('products.featured');

Route::get('/products/recent', [ProductController::class, 'recent'])
    ->name('products.recent');

Route::get('/products/stats', [ProductController::class, 'stats'])
    ->name('products.stats');

// Catálogo principal con filtros
Route::get('/products', [ProductController::class, 'index'])
    ->name('products.index');

// Productos relacionados (debe ir ANTES de show para evitar conflicto)
Route::get('/products/{slug}/related', [ProductController::class, 'related'])
    ->name('products.related');

// Ficha de producto individual
Route::get('/products/{slug}', [ProductController::class, 'show'])
    ->name('products.show');

// CATEGORÍAS
Route::get('/categories', [CategoryController::class, 'index'])
    ->name('categories.index');

Route::get('/categories/{slug}', [CategoryController::class, 'show'])
    ->name('categories.show');

// CONTACTO
// Enviar formulario "Me interesa"
Route::post('/contact', [ContactController::class, 'store'])
    ->name('contact.store')
    ->middleware('throttle:10,1'); // Máximo 10 envíos por minuto por IP

/*
|--------------------------------------------------------------------------
| Rutas Administrativas - Requieren autenticación (Fase 3)
|--------------------------------------------------------------------------
|
| Estas rutas estarán protegidas con Sanctum en la Fase 3
| Por ahora están disponibles públicamente para testing
|
*/

Route::prefix('admin')->group(function () {
    
    // Gestión de solicitudes de contacto
    Route::get('/contact', [ContactController::class, 'index'])
        ->name('admin.contact.index');
    
    Route::get('/contact/stats', [ContactController::class, 'stats'])
        ->name('admin.contact.stats');
    
    Route::patch('/contact/{id}/status/{status}', [ContactController::class, 'updateStatus'])
        ->name('admin.contact.update-status')
        ->whereIn('status', ['pendiente', 'contactado', 'cerrado']);
    
    // En la Fase 3 agregaremos aquí:
    // - CRUD completo de productos
    // - Subida de imágenes
    // - Gestión de categorías
    // - Dashboard con estadísticas
});

/*
|--------------------------------------------------------------------------
| Ruta de Health Check
|--------------------------------------------------------------------------
|
| Endpoint simple para verificar que la API está funcionando.
| Útil para monitoreo y testing.
|
*/

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'ReSirve API is running',
        'timestamp' => now()->toIso8601String(),
        'version' => '1.0.0',
    ]);
})->name('health');

/*
|--------------------------------------------------------------------------
| Ruta 404 para API
|--------------------------------------------------------------------------
|
| Captura todas las rutas no definidas y retorna un JSON 404
| en lugar del error HTML por defecto de Laravel.
|
*/

Route::fallback(function () {
    return response()->json([
        'message' => 'Endpoint no encontrado. Verifica la URL.',
        'available_endpoints' => [
            'products' => [
                'GET /api/products',
                'GET /api/products/featured',
                'GET /api/products/recent',
                'GET /api/products/stats',
                'GET /api/products/{slug}',
                'GET /api/products/{slug}/related',
            ],
            'categories' => [
                'GET /api/categories',
                'GET /api/categories/{slug}',
            ],
            'contact' => [
                'POST /api/contact',
            ],
            'admin' => [
                'GET /api/admin/contact',
                'GET /api/admin/contact/stats',
                'PATCH /api/admin/contact/{id}/status/{status}',
            ],
            'system' => [
                'GET /api/health',
            ],
        ],
    ], 404);
});