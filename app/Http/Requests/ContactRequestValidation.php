<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * ContactRequestValidation
 * 
 * Valida los datos del formulario "Me interesa" antes de procesarlos.
 * Define las reglas de validación y los mensajes de error personalizados.
 */
class ContactRequestValidation extends FormRequest
{
    /**
     * Determinar si el usuario está autorizado a hacer esta petición
     * 
     * Para el formulario de contacto, cualquiera puede enviarlo
     * sin necesidad de autenticación.
     * 
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación
     * 
     * @return array
     */
    public function rules(): array
    {
        return [
            'product_id' => [
                'required',
                'integer',
                'exists:products,id',
            ],
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
            ],
            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],
            'message' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    /**
     * Mensajes de error personalizados
     * 
     * @return array
     */
    public function messages(): array
    {
        return [
            'product_id.required' => 'Debes seleccionar un producto.',
            'product_id.exists' => 'El producto seleccionado no existe.',
            
            'name.required' => 'Por favor ingresa tu nombre.',
            'name.min' => 'El nombre debe tener al menos 2 caracteres.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            
            'email.required' => 'Por favor ingresa tu correo electrónico.',
            'email.email' => 'Por favor ingresa un correo electrónico válido.',
            'email.max' => 'El correo electrónico no puede tener más de 255 caracteres.',
            
            'phone.max' => 'El teléfono no puede tener más de 50 caracteres.',
            
            'message.max' => 'El mensaje no puede tener más de 1000 caracteres.',
        ];
    }

    /**
     * Nombres de atributos personalizados para los mensajes de error
     * 
     * @return array
     */
    public function attributes(): array
    {
        return [
            'product_id' => 'producto',
            'name' => 'nombre',
            'email' => 'correo electrónico',
            'phone' => 'teléfono',
            'message' => 'mensaje',
        ];
    }

    /**
     * Manejar una validación fallida
     * 
     * Sobrescribimos este método para retornar errores en formato JSON
     * consistente con el resto de la API.
     * 
     * @param Validator $validator
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Los datos enviados no son válidos.',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
