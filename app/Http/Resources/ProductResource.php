<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * ProductResource
 * 
 * Transforma un modelo Product en un array JSON con el formato
 * correcto para el frontend. Controla exactamente qué campos
 * se incluyen y cómo se formatean.
 */
class ProductResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'story' => $this->story,
            'price' => $this->price,
            'formatted_price' => $this->formatted_price,
            'condition' => $this->condition,
            'condition_text' => $this->condition_text,
            'status' => $this->status,
            'status_text' => $this->status_text,
            'is_featured' => $this->is_featured,
            'views_count' => $this->views_count,
            'url' => $this->url,
            
            // Relaciones
            'category' => new CategoryResource($this->whenLoaded('category')),
            
            // Imágenes: incluir todas si están cargadas, o solo la principal
            'images' => ProductImageResource::collection($this->whenLoaded('images')),
            'primary_image' => new ProductImageResource($this->whenLoaded('primaryImage')),
            
            // Fechas
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'created_at_human' => $this->created_at?->diffForHumans(),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
