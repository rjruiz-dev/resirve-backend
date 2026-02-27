<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Consulta - ReSirve</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #2c3e50;
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .product-info {
            background-color: #ecf0f1;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .product-info h2 {
            margin-top: 0;
            color: #2c3e50;
            font-size: 20px;
        }
        .product-info p {
            margin: 5px 0;
        }
        .contact-info {
            background-color: #e8f5e9;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .contact-info h3 {
            margin-top: 0;
            color: #27ae60;
        }
        .info-row {
            margin: 10px 0;
        }
        .info-label {
            font-weight: bold;
            color: #555;
        }
        .message-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 12px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #27ae60;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
        }
        .price {
            font-size: 24px;
            color: #27ae60;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔔 Nueva Consulta en ReSirve</h1>
        </div>
        
        <div class="content">
            <p>¡Hola! Recibiste una nueva consulta sobre uno de tus productos.</p>
            
            <div class="product-info">
                <h2>📦 Producto de Interés</h2>
                <p><strong>{{ $product->title }}</strong></p>
                <p class="price">{{ $product->formatted_price }}</p>
                <p>Estado: {{ $product->condition_text }}</p>
                <p>Categoría: {{ $product->category->name }}</p>
            </div>
            
            <div class="contact-info">
                <h3>👤 Datos del Interesado</h3>
                
                <div class="info-row">
                    <span class="info-label">Nombre:</span>
                    {{ $contactRequest->name }}
                </div>
                
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <a href="mailto:{{ $contactRequest->email }}">{{ $contactRequest->email }}</a>
                </div>
                
                @if($contactRequest->phone)
                <div class="info-row">
                    <span class="info-label">Teléfono:</span>
                    <a href="tel:{{ $contactRequest->phone }}">{{ $contactRequest->phone }}</a>
                </div>
                @endif
            </div>
            
            @if($contactRequest->message)
            <div class="message-box">
                <p><strong>💬 Mensaje:</strong></p>
                <p>{{ $contactRequest->message }}</p>
            </div>
            @endif
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="mailto:{{ $contactRequest->email }}" class="btn">
                    📧 Responder por Email
                </a>
                
                @if($contactRequest->phone)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contactRequest->phone) }}" class="btn" style="background-color: #25D366;">
                    📱 Contactar por WhatsApp
                </a>
                @endif
            </div>
        </div>
        
        <div class="footer">
            <p>Este email fue generado automáticamente por ReSirve.</p>
            <p>Consulta recibida el {{ $contactRequest->created_at->format('d/m/Y') }} a las {{ $contactRequest->created_at->format('H:i') }} hs</p>
        </div>
    </div>
</body>
</html>
