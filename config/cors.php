<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Aquí configuramos qué orígenes pueden acceder a nuestra API.
    | Esto es fundamental para que Angular (localhost:4200) pueda
    | hacer peticiones a Laravel (localhost:8000) sin problemas.
    |
    */

    /*
     | Paths que usarán esta configuración de CORS
     | El asterisco significa "todas las rutas que empiecen con api/"
     */
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    /*
     | Métodos HTTP permitidos
     */
    'allowed_methods' => ['*'], // GET, POST, PUT, PATCH, DELETE, OPTIONS

    /*
     | Orígenes permitidos
     | 
     | DESARROLLO: Permitimos localhost en varios puertos para Angular, React, etc.
     | PRODUCCIÓN: Deberías cambiar esto a tu dominio específico
     |             Ejemplo: ['https://resirve.com', 'https://www.resirve.com']
     */
    'allowed_origins' => [
        'http://localhost:4200',      // Angular por defecto
        'http://localhost:3000',      // React por si cambias a futuro
        'http://127.0.0.1:4200',      // Variante de localhost
        'http://127.0.0.1:3000',
    ],

    /*
     | Patrones de origen permitidos (más flexible que allowed_origins)
     | Útil si usas subdominios dinámicos en producción
     */
    'allowed_origins_patterns' => [],

    /*
     | Headers que el cliente puede enviar
     */
    'allowed_headers' => ['*'],

    /*
     | Headers que se expondrán al cliente en la respuesta
     */
    'exposed_headers' => [],

    /*
     | Tiempo máximo que el navegador cachea la configuración de CORS (en segundos)
     */
    'max_age' => 0,

    /*
     | Permitir credenciales (cookies, authorization headers)
     | Necesario para autenticación con Sanctum
     */
    'supports_credentials' => true,

];