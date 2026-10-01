<?php

namespace Database\Factories;

use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Categoria>
 */
class CategoriaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([ // nombre
                'Filosofía',
                'Biología',
                'Arte',
                'Economía',
                'Derecho',
                'Geografía',
                'Música',
                'Química',
                'Astronomía',
                'Lingüística',
                'Psicología',
                'Sociología',
                'Arquitectura',
                'Medicina',
                'Física',
                'Antropología',
                'Ecología',
                'Periodismo',
            ]),
        ];
    }
}
