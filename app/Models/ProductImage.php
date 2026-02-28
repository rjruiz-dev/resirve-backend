<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo ProductImage
 * 
 * Representa las imágenes/fotos de un producto.
 * Un producto puede tener múltiples imágenes, y una de ellas
 * es marcada como la imagen principal.
 */
class ProductImage extends Model
{
    use HasFactory;

    /**
     * Atributos asignables en masa
     */
    protected $fillable = [
        'product_id',
        'path',
        'is_primary',
        'order',
    ];

    /**
     * Casting de atributos a tipos nativos
     */
    protected $casts = [
        'is_primary' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Relación: Una imagen pertenece a un producto
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Obtener la URL completa de la imagen
     * 
     * Si estás usando Cloudinary o un CDN, aquí puedes construir
     * la URL completa. Por ahora asume que el path es relativo
     * al directorio de storage.
     */
    public function getFullUrlAttribute(): string
    {
        // Si el path ya es una URL completa (empieza con http)
        if (str_starts_with($this->path, 'http')) {
            return $this->path;
        }
        
        // Si es un path local, construye la URL
        return asset('storage/' . $this->path);
    }

    /**
     * Scope: Obtener solo imágenes principales
     * 
     * Uso: ProductImage::primary()->get()
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }
}