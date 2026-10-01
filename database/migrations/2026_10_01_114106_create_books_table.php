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
        Schema::create('books', function (Blueprint $table) { // libros
            $table->id();
            $table->string('title'); // titulo
            $table->string('isbn')->unique();
            $table->integer('publication_year'); // anio_publicacion
            $table->integer('quantity')->default(1); // cantidad
            $table->foreignId('author_id')->constrained('authors')->restrictOnDelete(); // autor_id → autores
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete(); // categoria_id → categorias
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books'); // libros
    }
};
