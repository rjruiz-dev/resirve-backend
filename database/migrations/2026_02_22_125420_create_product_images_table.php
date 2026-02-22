<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('path'); // Ruta donde está almacenada la imagen
            $table->boolean('is_primary')->default(false); // Foto principal del producto
            $table->unsignedInteger('order')->default(0); // Orden de visualización
            $table->timestamps();
            
            // Índice para optimizar la consulta de imágenes de un producto
            $table->index(['product_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
