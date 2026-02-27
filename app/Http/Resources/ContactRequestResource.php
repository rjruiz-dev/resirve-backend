<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * ContactRequestResource
 * 
 * Transforma un modelo ContactRequest en un array JSON.
 * Incluye información del producto relacionado y datos del contacto.
 */
class ContactRequestResource extends JsonResource
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
            'email' => $this->email,
            'phone' => $this->phone,
            'message' => $this->message,
            'status' => $this->status,
            'status_text' => $this->status_text,
            'contact_info' => $this->contact_info,
            
            // Producto relacionado (solo información básica)
            'product' => $this->when($this->relationLoaded('product'), function () {
                return [
                    'id' => $this->product->id,
                    'title' => $this->product->title,
                    'slug' => $this->product->slug,
                    'price' => $this->product->price,
                    'formatted_price' => $this->product->formatted_price,
                    'url' => $this->product->url,
                ];
            }),
            
            // Fechas
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'created_at_human' => $this->created_at?->diffForHumans(),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
