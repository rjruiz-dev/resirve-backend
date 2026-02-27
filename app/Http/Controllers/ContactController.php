<?php

namespace App\Http\Controllers;

use App\Models\ContactRequest;
use App\Models\Product;
use App\Http\Requests\ContactRequestValidation;
use App\Http\Resources\ContactRequestResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

/**
 * ContactController
 * 
 * Maneja las solicitudes de contacto del formulario "Me interesa".
 * Valida los datos, guarda el registro en la base de datos,
 * y envía un email de notificación al administrador.
 */
class ContactController extends Controller
{
    /**
     * Procesar una nueva solicitud de contacto
     * 
     * Flujo:
     * 1. Valida los datos del formulario
     * 2. Verifica que el producto exista
     * 3. Guarda el registro en la base de datos
     * 4. Envía email de notificación
     * 5. Retorna la respuesta al frontend
     * 
     * @param ContactRequestValidation $request
     * @return JsonResponse
     */
    public function store(ContactRequestValidation $request): JsonResponse
    {
        // Los datos ya están validados por ContactRequestValidation
        $validatedData = $request->validated();

        // Obtener el producto para incluir su información en el email
        $product = Product::findOrFail($validatedData['product_id']);

        // Capturar la IP del usuario (para protección contra spam)
        $validatedData['ip_address'] = $request->ip();

        // Crear el registro en la base de datos
        $contactRequest = ContactRequest::create($validatedData);

        // Cargar la relación del producto para el resource
        $contactRequest->load('product');

        // Intentar enviar el email de notificación
        try {
            $this->sendNotificationEmail($contactRequest, $product);
        } catch (\Exception $e) {
            // Si el email falla, registrar el error pero NO fallar la petición
            // El registro ya se guardó exitosamente en la base de datos
            Log::error('Error al enviar email de contacto: ' . $e->getMessage(), [
                'contact_request_id' => $contactRequest->id,
                'product_id' => $product->id,
            ]);
        }

        return response()->json([
            'message' => 'Tu solicitud fue enviada exitosamente. Te contactaremos pronto.',
            'data' => new ContactRequestResource($contactRequest),
            'whatsapp_link' => $this->generateWhatsAppLink($contactRequest, $product),
        ], 201);
    }

    /**
     * Enviar email de notificación al administrador
     * 
     * @param ContactRequest $contactRequest
     * @param Product $product
     * @return void
     */
    protected function sendNotificationEmail(ContactRequest $contactRequest, Product $product): void
    {
        $adminEmail = env('ADMIN_EMAIL', 'admin@resirve.com');
        
        Mail::send('emails.contact-notification', [
            'contactRequest' => $contactRequest,
            'product' => $product,
        ], function ($message) use ($adminEmail, $product) {
            $message->to($adminEmail)
                    ->subject('Nueva consulta sobre: ' . $product->title);
        });
    }

    /**
     * Generar enlace de WhatsApp prellenado
     * 
     * Crea un link de WhatsApp con el mensaje prellenado
     * para facilitar el contacto directo.
     * 
     * @param ContactRequest $contactRequest
     * @param Product $product
     * @return string
     */
    protected function generateWhatsAppLink(ContactRequest $contactRequest, Product $product): string
    {
        $phoneNumber = env('WHATSAPP_NUMBER', '5493425365656'); 
        
        $message = "Hola! Me interesa el producto: *{$product->title}*\n\n";
        $message .= "Mi nombre es {$contactRequest->name}.\n";
        
        if ($contactRequest->message) {
            $message .= "Consulta: {$contactRequest->message}";
        }

        return 'https://wa.me/' . $phoneNumber . '?text=' . urlencode($message);
    }

    /**
     * Listar todas las solicitudes de contacto (para el panel de admin)
     * 
     * Este método será útil en la Fase 3 cuando implementemos
     * el panel de administración.
     * 
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $requests = ContactRequest::with('product')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'data' => ContactRequestResource::collection($requests),
            'pagination' => [
                'total' => $requests->total(),
                'per_page' => $requests->perPage(),
                'current_page' => $requests->currentPage(),
                'last_page' => $requests->lastPage(),
            ]
        ]);
    }

    /**
     * Obtener estadísticas de solicitudes
     * 
     * Retorna contadores por status para el dashboard del admin.
     * 
     * @return JsonResponse
     */
    public function stats(): JsonResponse
    {
        return response()->json([
            'total' => ContactRequest::count(),
            'pending' => ContactRequest::pending()->count(),
            'contacted' => ContactRequest::contacted()->count(),
            'closed' => ContactRequest::closed()->count(),
        ]);
    }

    /**
     * Actualizar el status de una solicitud
     * 
     * Permite marcar una solicitud como contactada o cerrada.
     * 
     * @param int $id
     * @param string $status
     * @return JsonResponse
     */
    public function updateStatus(int $id, string $status): JsonResponse
    {
        $validStatuses = ['pendiente', 'contactado', 'cerrado'];
        
        if (!in_array($status, $validStatuses)) {
            return response()->json([
                'message' => 'Status inválido',
            ], 400);
        }

        $contactRequest = ContactRequest::findOrFail($id);
        $contactRequest->update(['status' => $status]);

        return response()->json([
            'message' => 'Status actualizado exitosamente',
            'data' => new ContactRequestResource($contactRequest),
        ]);
    }
}
