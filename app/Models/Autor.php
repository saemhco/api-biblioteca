<?php

namespace App\Models;

use Database\Factories\AutorFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Autor extends Model
{
    /** @use HasFactory<AutorFactory> */
    use HasFactory;

    protected $table = 'authors'; // autores

    protected $fillable = [
        'name', // nombre
        'nationality', // nacionalidad
    ];

    public function libros(): HasMany
    {
        return $this->hasMany(Libro::class, 'author_id'); // autor_id
    }
}
