<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener las categorías por slug para asignarlas
        $categorias = [
            'electrodomesticos' => Category::where('slug', 'electrodomesticos')->first(),
            'muebles' => Category::where('slug', 'muebles')->first(),
            'deportes' => Category::where('slug', 'deportes')->first(),
            'tecnologia' => Category::where('slug', 'tecnologia')->first(),
            'otros' => Category::where('slug', 'otros')->first(),
        ];

        // Producto 1: Bicicleta de Montaña
        $bicicleta = Product::create([
            'title' => 'Bicicleta de Montaña Rodado 26',
            'slug' => 'bicicleta-montana-rodado-26',
            'description' => 'Bicicleta de montaña rodado 26 con cambios Shimano de 18 velocidades. Cuadro de acero reforzado, suspensión delantera con 80mm de recorrido, frenos V-Brake. Cubiertas con buen dibujo para terrenos mixtos. Asiento regulable y manubrio ergonómico. Estado mecánico excelente, lista para usar.',
            'story' => 'Esta bicicleta fue comprada en 2020 para paseos por la costanera y ocasionalmente por el cerro. Siempre se guardó bajo techo y se le hizo mantenimiento regular en bicicletería. La cadena y los cambios están en perfecto estado. La vendo porque me mudé a un departamento más chico y no tengo dónde guardarla.',
            'price' => 85000.00,
            'category_id' => $categorias['deportes']->id,
            'condition' => 'buen_estado',
            'status' => 'disponible',
            'is_featured' => true,
        ]);

        // Crear imágenes para la bicicleta
        ProductImage::create([
            'product_id' => $bicicleta->id,
            'path' => 'https://images.unsplash.com/photo-1576435728678-68d0fbf94e91?w=800',
            'is_primary' => true,
            'order' => 1,
        ]);

        ProductImage::create([
            'product_id' => $bicicleta->id,
            'path' => 'https://images.unsplash.com/photo-1511994298241-608e28f14fde?w=800',
            'is_primary' => false,
            'order' => 2,
        ]);

        // Producto 2: Heladera Gafa
        $heladera = Product::create([
            'title' => 'Heladera Gafa HGF-366 con Freezer',
            'slug' => 'heladera-gafa-hgf-366-freezer',
            'description' => 'Heladera con freezer Gafa modelo HGF-366. Capacidad 334 litros, freezer de 66 litros. Sistema de frío húmedo (no frost). Burlete en perfecto estado, cierre hermético. 3 estantes de vidrio templado, cajones para verduras y frutas. Freezer con capacidad para 4 cubiteras. Altura: 185cm, Ancho: 62cm, Profundidad: 68cm. Consumo eficiente clase A.',
            'story' => 'La compramos hace 5 años cuando nos mudamos. Funcionó impecable todo este tiempo, nunca necesitó service técnico. La cambiamos por una más grande porque agrandamos la familia. Está limpia y desinfectada, lista para ser retirada.',
            'price' => 320000.00,
            'category_id' => $categorias['electrodomesticos']->id,
            'condition' => 'buen_estado',
            'status' => 'disponible',
            'is_featured' => true,
        ]);

        ProductImage::create([
            'product_id' => $heladera->id,
            'path' => 'https://images.unsplash.com/photo-1571175443880-49e1d25b2bc5?w=800',
            'is_primary' => true,
            'order' => 1,
        ]);

        // Producto 3: Notebook HP
        $notebook = Product::create([
            'title' => 'Notebook HP 15-dy2021la Core i5 11va Gen',
            'slug' => 'notebook-hp-15-dy2021la-core-i5',
            'description' => 'Notebook HP de 15.6 pulgadas. Procesador Intel Core i5-1135G7 (11va generación), 8GB RAM DDR4, Disco SSD 256GB. Pantalla Full HD (1920x1080), placa de video Intel Iris Xe integrada. Teclado en español con pad numérico. Batería con 4-5 horas de autonomía. Windows 11 Home original. Incluye cargador original HP.',
            'story' => 'La usé durante 2 años para trabajo remoto y estudio. Siempre funcionó perfecto, nunca se le hizo upgrade de hardware. La batería mantiene buena carga. La carcasa tiene alguna marca de uso pero nada grave. La pantalla está impecable sin rayones. La cambio por una notebook más potente para edición de video.',
            'price' => 450000.00,
            'category_id' => $categorias['tecnologia']->id,
            'condition' => 'buen_estado',
            'status' => 'disponible',
            'is_featured' => false,
        ]);

        ProductImage::create([
            'product_id' => $notebook->id,
            'path' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800',
            'is_primary' => true,
            'order' => 1,
        ]);

        ProductImage::create([
            'product_id' => $notebook->id,
            'path' => 'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=800',
            'is_primary' => false,
            'order' => 2,
        ]);

        // Producto 4: Escritorio de Madera
        $escritorio = Product::create([
            'title' => 'Escritorio de Madera Maciza con Cajonera',
            'slug' => 'escritorio-madera-maciza-cajonera',
            'description' => 'Escritorio fabricado en madera maciza de paraíso. Superficie de trabajo de 140cm x 70cm. Altura estándar de 75cm. Incluye cajonera lateral con 3 cajones con rieles metálicos que deslizan suavemente. Acabado con barniz mate que resalta la veta natural de la madera. Muy sólido y estable, capacidad de carga de hasta 80kg. Ideal para home office o estudio.',
            'story' => 'Este escritorio fue hecho a medida por un carpintero hace unos 8 años. Lo usé durante toda la pandemia para trabajar desde casa. Es súper cómodo y nunca cruje ni se mueve. La madera está en excelente estado, solo tiene pequeñas marcas de uso normal que le dan carácter. Lo vendo porque cambié la distribución del departamento y ahora trabajo en otro espacio.',
            'price' => 75000.00,
            'category_id' => $categorias['muebles']->id,
            'condition' => 'buen_estado',
            'status' => 'reservado',
            'is_featured' => false,
        ]);

        ProductImage::create([
            'product_id' => $escritorio->id,
            'path' => 'https://images.unsplash.com/photo-1518455027359-f3f8164ba6bd?w=800',
            'is_primary' => true,
            'order' => 1,
        ]);

        // Producto 5: Televisor Samsung Smart TV
        $televisor = Product::create([
            'title' => 'Smart TV Samsung 43" Full HD',
            'slug' => 'smart-tv-samsung-43-full-hd',
            'description' => 'Smart TV Samsung de 43 pulgadas con resolución Full HD (1920x1080). Sistema operativo Tizen con acceso a Netflix, YouTube, Prime Video y otras apps. Conectividad WiFi y Bluetooth. 2 puertos HDMI, 1 puerto USB. Control remoto original incluido. Soporte de pared VESA incluido. Excelente calidad de imagen y sonido.',
            'story' => 'Lo compré hace 3 años y funcionó perfectamente siempre. Lo usábamos principalmente para ver series y películas. Nunca tuvo ningún problema técnico, los píxeles están perfectos sin manchas ni líneas. Lo cambio por un modelo más grande para la nueva sala de estar.',
            'price' => 180000.00,
            'category_id' => $categorias['tecnologia']->id,
            'condition' => 'como_nuevo',
            'status' => 'vendido',
            'is_featured' => false,
        ]);

        ProductImage::create([
            'product_id' => $televisor->id,
            'path' => 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=800',
            'is_primary' => true,
            'order' => 1,
        ]);

        // Producto 6: Microondas Philco
        $microondas = Product::create([
            'title' => 'Microondas Philco 20 Litros PMO2038D',
            'slug' => 'microondas-philco-20-litros',
            'description' => 'Microondas Philco de 20 litros de capacidad. Potencia 700W, 6 niveles de potencia ajustables. Función descongelar por peso o tiempo. Plato giratorio de vidrio de 25.5cm. Timer hasta 35 minutos. Display digital con reloj. Dimensiones: 44cm ancho x 35cm profundidad x 26cm alto. Color blanco. Funciona perfectamente.',
            'story' => 'Lo compramos hace 2 años y medio. Siempre lo usamos para calentar comida y descongelar, nunca para cocinar por eso está como nuevo. Se limpia después de cada uso. Lo vendemos porque compramos un microondas con grill más grande.',
            'price' => 45000.00,
            'category_id' => $categorias['electrodomesticos']->id,
            'condition' => 'como_nuevo',
            'status' => 'disponible',
            'is_featured' => false,
        ]);

        ProductImage::create([
            'product_id' => $microondas->id,
            'path' => 'https://images.unsplash.com/photo-1585659722983-3a675dabf23d?w=800',
            'is_primary' => true,
            'order' => 1,
        ]);

        // Producto 7: Juego de Pesas
        $pesas = Product::create([
            'title' => 'Set de Mancuernas Regulables 2x20kg',
            'slug' => 'set-mancuernas-regulables-2x20kg',
            'description' => 'Par de mancuernas regulables de hierro fundido. Cada mancuerna puede cargarse hasta 20kg. Incluye: 2 barras de 35cm con cierre de rosca, 16 discos de peso (4x1kg, 4x2kg, 4x2.5kg, 4x5kg), 4 trabas de seguridad metálicas. Recubrimiento de pintura negra resistente. Agarre antideslizante en las barras. Total: 40kg de peso entre ambas mancuernas.',
            'story' => 'Las compré durante la cuarentena para entrenar en casa. Las usé regularmente por un año, están en muy buen estado sin óxido. Ahora me anoté en el gimnasio y no las uso más. Son excelentes para quien quiere empezar a entrenar fuerza en casa sin gastar mucho.',
            'price' => 55000.00,
            'category_id' => $categorias['deportes']->id,
            'condition' => 'buen_estado',
            'status' => 'disponible',
            'is_featured' => false,
        ]);

        ProductImage::create([
            'product_id' => $pesas->id,
            'path' => 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=800',
            'is_primary' => true,
            'order' => 1,
        ]);

        $this->command->info('✓ 7 productos creados exitosamente con sus imágenes');
    }
}
