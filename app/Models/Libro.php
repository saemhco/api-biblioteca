<?php

namespace App\Models;

use Database\Factories\LibroFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Libro extends Model
{
    /** @use HasFactory<LibroFactory> */
    use HasFactory;

    protected $table = 'books'; // libros

    protected $fillable = [
        'title', // titulo
        'isbn',
        'publication_year', // anio_publicacion
        'quantity', // cantidad
        'author_id', // autor_id
        'category_id', // categoria_id
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'publication_year' => 'integer', // anio_publicacion
            'quantity' => 'integer', // cantidad
        ];
    }

    public function autor(): BelongsTo
    {
        return $this->belongsTo(Autor::class, 'author_id'); // autor_id
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'category_id'); // categoria_id
    }
}
