<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo Category
 * 
 * Representa las categorías que organizan los productos.
 * Ejemplos: Electrodomésticos, Muebles, Deportes, Tecnología.
 */
class Category extends Model
{
    use HasFactory;

    /**
     * Atributos asignables en masa
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
    ];

    /**
     * Relación: Una categoría tiene muchos productos
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Relación: Productos disponibles de esta categoría
     * 
     * Esta es una versión filtrada de la relación products()
     * que solo devuelve productos con status "disponible".
     * Útil para el catálogo público.
     */
    public function availableProducts(): HasMany
    {
        return $this->hasMany(Product::class)
            ->where('status', 'disponible');
    }

    /**
     * Obtener la URL amigable de la categoría
     */
    public function getUrlAttribute(): string
    {
        return "/categoria/{$this->slug}";
    }
}