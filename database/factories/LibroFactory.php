<?php

namespace Database\Factories;

use App\Models\Autor;
use App\Models\Categoria;
use App\Models\Libro;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Libro>
 */
class LibroFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->titulo(), // titulo
            'isbn' => fake()->unique()->isbn13(),
            'publication_year' => fake()->numberBetween(1950, (int) date('Y')), // anio_publicacion
            'quantity' => fake()->numberBetween(1, 15), // cantidad
            'author_id' => Autor::factory(), // autor_id
            'category_id' => Categoria::factory(), // categoria_id
        ];
    }

    private function titulo(): string
    {
        $inicio = fake()->randomElement([
            'El silencio',
            'La sombra',
            'Los cuadernos',
            'Las cartas',
            'El último verano',
            'La casa',
            'El río',
            'La memoria',
            'Los puentes',
            'El jardín',
            'La lluvia',
            'El faro',
            'Crónica',
            'Diario',
            'Historia breve',
            'Mapa',
        ]);

        $cierre = fake()->randomElement([
            'del puerto',
            'de los naranjos',
            'en invierno',
            'de la frontera',
            'del altiplano',
            'de octubre',
            'sin nombre',
            'de papel',
            'de una isla',
            'de los Andes',
            'en voz baja',
            'de Cádiz',
            'de Lima',
            'de Quito',
            'de Oaxaca',
            'del desierto',
            'de la biblioteca',
            'de un naufragio',
        ]);

        return $inicio.' '.$cierre;
    }
}
