<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * CategoryResource
 * 
 * Transforma un modelo Category en un array JSON con el formato
 * correcto para el frontend.
 */
class CategoryResource extends JsonResource
{
    /**
     * Transformar el recurso en un array
     * 
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'icon' => $this->icon,
            'url' => $this->url,
            
            // Contadores (solo si fueron cargados con withCount)
            'products_count' => $this->when(isset($this->products_count), $this->products_count),
            'available_products_count' => $this->when(
                isset($this->available_products_count), 
                $this->available_products_count
            ),
            
            // Productos (solo si se cargó la relación)
            'products' => ProductResource::collection($this->whenLoaded('products')),
            'available_products' => ProductResource::collection($this->whenLoaded('availableProducts')),
            
            // Fechas
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}