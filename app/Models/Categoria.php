<?php

namespace App\Models;

use Database\Factories\CategoriaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    /** @use HasFactory<CategoriaFactory> */
    use HasFactory;

    protected $table = 'categories'; // categorias

    protected $fillable = [
        'name', // nombre
    ];

    public function libros(): HasMany
    {
        return $this->hasMany(Libro::class, 'category_id'); // categoria_id
    }
}
