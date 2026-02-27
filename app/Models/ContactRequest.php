<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo ContactRequest
 * 
 * Representa las solicitudes de contacto que envían los usuarios
 * cuando están interesados en un producto.
 */
class ContactRequest extends Model
{
    use HasFactory;

    /**
     * Atributos asignables en masa
     */
    protected $fillable = [
        'product_id',
        'name',
        'email',
        'phone',
        'message',
        'status',
        'ip_address',
    ];

    /**
     * Casting de atributos a tipos nativos
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación: Una solicitud pertenece a un producto
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope: Filtrar solo solicitudes pendientes
     * 
     * Uso: ContactRequest::pending()->get()
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pendiente');
    }

    /**
     * Scope: Filtrar solo solicitudes contactadas
     * 
     * Uso: ContactRequest::contacted()->get()
     */
    public function scopeContacted($query)
    {
        return $query->where('status', 'contactado');
    }

    /**
     * Scope: Filtrar solo solicitudes cerradas
     * 
     * Uso: ContactRequest::closed()->get()
     */
    public function scopeClosed($query)
    {
        return $query->where('status', 'cerrado');
    }

    /**
     * Marcar la solicitud como contactada
     */
    public function markAsContacted(): void
    {
        $this->update(['status' => 'contactado']);
    }

    /**
     * Marcar la solicitud como cerrada
     */
    public function markAsClosed(): void
    {
        $this->update(['status' => 'cerrado']);
    }

    /**
     * Obtener el status en texto legible
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'pendiente' => 'Pendiente',
            'contactado' => 'Contactado',
            'cerrado' => 'Cerrado',
            default => $this->status,
        };
    }

    /**
     * Obtener información formateada del contacto
     */
    public function getContactInfoAttribute(): string
    {
        $info = [];
        
        if ($this->email) {
            $info[] = "Email: {$this->email}";
        }
        
        if ($this->phone) {
            $info[] = "Teléfono: {$this->phone}";
        }
        
        return implode(' | ', $info);
    }
}
