<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * ProductImageResource
 * 
 * Transforma un modelo ProductImage en un array JSON.
 * Incluye la URL completa de la imagen y metadatos.
 */
class ProductImageResource extends JsonResource
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
            'path' => $this->path,
            'full_url' => $this->full_url,
            'is_primary' => $this->is_primary,
            'order' => $this->order,
        ];
    }
}