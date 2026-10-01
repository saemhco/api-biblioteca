<?php

namespace Database\Seeders;

use App\Models\Autor;
use App\Models\Categoria;
use App\Models\Libro;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $autores = Autor::factory(10)->create();

        $categorias = collect([
            'Novela',
            'Ciencias',
            'Historia',
            'Matemáticas',
            'Poesía',
            'Tecnología',
        ])->map(fn (string $nombre) => Categoria::factory()->create([
            'name' => $nombre, // nombre
        ]));

        Libro::factory(40)
            ->recycle($autores)
            ->recycle($categorias)
            ->create();
    }
}
