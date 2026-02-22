<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electrodomésticos',
                'slug' => 'electrodomesticos',
                'description' => 'Heladeras, lavarropas, microondas, licuadoras y otros electrodomésticos para el hogar',
                'icon' => 'bi-plug',
            ],
            [
                'name' => 'Muebles',
                'slug' => 'muebles',
                'description' => 'Sillas, mesas, escritorios, camas, bibliotecas y otros muebles para el hogar',
                'icon' => 'bi-house',
            ],
            [
                'name' => 'Deportes',
                'slug' => 'deportes',
                'description' => 'Bicicletas, pesas, pelotas, ropa deportiva y artículos para actividades físicas',
                'icon' => 'bi-bicycle',
            ],
            [
                'name' => 'Tecnología',
                'slug' => 'tecnologia',
                'description' => 'Notebooks, tablets, celulares, televisores, consolas y equipos tecnológicos',
                'icon' => 'bi-laptop',
            ],
            [
                'name' => 'Otros',
                'slug' => 'otros',
                'description' => 'Artículos diversos que no entran en las categorías anteriores',
                'icon' => 'bi-box',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $this->command->info('✓ 5 categorías creadas exitosamente');
    }
}
