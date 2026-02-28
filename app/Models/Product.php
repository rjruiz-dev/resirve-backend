<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Modelo Product
 * 
 * Representa los artículos usados que estás vendiendo en ReSirve.
 * Este es el modelo central de toda la aplicación.
 */
class Product extends Model
{
    use HasFactory;

    /**
     * Atributos asignables en masa
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'story',
        'price',
        'category_id',
        'condition',
        'status',
        'is_featured',
    ];

    /**
     * Casting de atributos a tipos nativos
     */
    protected $casts = [
        'price' => 'decimal:2',
        'is_featured' => 'boolean',
        'views_count' => 'integer',
    ];

    /**
     * Relación: Un producto pertenece a una categoría
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relación: Un producto tiene muchas imágenes
     * 
     * Las imágenes se retornan ordenadas por el campo "order"
     * para que siempre aparezcan en la secuencia correcta.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('order');
    }

    /**
     * Relación: Obtener solo la imagen principal del producto
     * 
     * Esta es una optimización para cuando solo necesitas la foto principal
     * y no quieres cargar todas las imágenes.
     */
    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    /**
     * Relación: Un producto puede tener muchas solicitudes de contacto
     */
    public function contactRequests(): HasMany
    {
        return $this->hasMany(ContactRequest::class);
    }

    /**
     * Incrementar el contador de visitas
     * 
     * Este método se llama cada vez que alguien ve la ficha del producto.
     * Incrementa el campo views_count sin necesidad de cargar el modelo completo.
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Scope: Filtrar solo productos disponibles
     * 
     * Uso: Product::available()->get()
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'disponible');
    }

    /**
     * Scope: Filtrar solo productos destacados
     * 
     * Uso: Product::featured()->get()
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Obtener la URL amigable del producto
     */
    public function getUrlAttribute(): string
    {
        return "/productos/{$this->slug}";
    }

    /**
     * Obtener el precio formateado con símbolo de moneda
     */
    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->price, 2, ',', '.');
    }

    /**
     * Obtener el estado de conservación en texto legible
     */
    public function getConditionTextAttribute(): string
    {
        return match($this->condition) {
            'como_nuevo' => 'Como Nuevo',
            'buen_estado' => 'Buen Estado',
            'funcional' => 'Funcional',
            default => $this->condition,
        };
    }

    /**
     * Obtener el status en texto legible
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'disponible' => 'Disponible',
            'reservado' => 'Reservado',
            'vendido' => 'Vendido',
            default => $this->status,
        };
    }
}