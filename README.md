# biblioteca-api

Proyecto base en Laravel para la evaluación de la API de biblioteca. Incluye únicamente la capa de datos: modelos, migraciones, factories y seeders. Las rutas, los controladores y el trait de respuestas los implementan los estudiantes.

## Requisitos

- PHP 8.2 o superior
- Composer
- MySQL
- Extensión PDO MySQL

La base de datos es MySQL. El nombre por defecto es `biblioteca`.

## Instalación

Clona el repositorio y entra en la carpeta del proyecto:

```bash
git clone https://github.com/saemhco/api-biblioteca.git
cd api-biblioteca
composer install
cp .env.example .env
php artisan key:generate
mysql -u root -e "CREATE DATABASE IF NOT EXISTS biblioteca CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php artisan migrate:fresh --seed
php artisan serve
```

`APP_FAKER_LOCALE=es_ES` ya está definido en `.env.example`, así que Faker genera nombres, países y textos en español.

La API queda en `http://localhost:8000/api`. El archivo `routes/api.php` ya existe (se creó con `php artisan install:api`) y solo trae la ruta de ejemplo de Sanctum. Ahí se agregan las rutas del examen.

## Modelos

Las tablas y las columnas están en inglés. En modelos, migraciones y factories hay un comentario con el nombre en español (`name` es nombre, `authors` es autores). Cada modelo define `$table` de forma explícita. `Pluralizer::useLanguage('spanish')` solo afecta a los parámetros de las rutas resource: `autores` → `{autor}`, `categorias` → `{categoria}`, `libros` → `{libro}`.

### Autor (`authors`, autores)

| Columna | En español | Tipo |
| --- | --- | --- |
| id | id | bigint |
| name | nombre | string |
| nationality | nacionalidad | string |
| created_at, updated_at | timestamps | timestamps |

Relación: `libros()` — un autor tiene muchos libros (`hasMany`, clave `author_id`).

### Categoria (`categories`, categorias)

| Columna | En español | Tipo |
| --- | --- | --- |
| id | id | bigint |
| name | nombre | string, único |
| created_at, updated_at | timestamps | timestamps |

Relación: `libros()` — una categoría tiene muchos libros (`hasMany`, clave `category_id`).

Nombres sembrados en `name`: Novela, Ciencias, Historia, Matemáticas, Poesía y Tecnología.

### Libro (`books`, libros)

| Columna | En español | Tipo |
| --- | --- | --- |
| id | id | bigint |
| title | titulo | string |
| isbn | isbn | string, único |
| publication_year | anio_publicacion | integer |
| quantity | cantidad | integer, valor por defecto 1 |
| author_id | autor_id | clave foránea a `authors` |
| category_id | categoria_id | clave foránea a `categories` |
| created_at, updated_at | timestamps | timestamps |

Relaciones:

- `autor()` — pertenece a un autor (`belongsTo`, clave `author_id`)
- `categoria()` — pertenece a una categoría (`belongsTo`, clave `category_id`)

`publication_year` y `quantity` se castean a integer.

El seeder crea 10 autores, las 6 categorías fijas y 40 libros repartidos al azar entre esos autores y categorías.

## Claves foráneas

`author_id` y `category_id` usan `restrictOnDelete`. La base de datos rechaza el borrado de un autor o de una categoría que todavía tenga libros. `destroy` debe capturar ese fallo (por ejemplo una `QueryException`) y responder con un error; si no, la petición termina en una excepción sin controlar.

## Examen

Las rutas van en `routes/api.php`. Todos los controladores usan el trait. No hace falta crear Form Requests ni API Resources: la validación y el formato de salida pueden vivir en los controladores.

1. **Crea el trait `ApiResponse`.** En `app/Traits` implementa `success($data, $message = 'OK', $code = 200)` y `error($message, $code = 400, $errors = null)`. Úsalo en todos los controladores. La respuesta JSON lleva `success`, `message`, `code` y, según el caso, `data` o `errors`. El mismo `$code` va en el cuerpo y como estado HTTP:

   ```php
   // success
   return response()->json([
       'success' => true,
       'message' => $message,
       'code' => $code,
       'data' => $data,
   ], $code);

   // error ($errors solo si no es null)
   return response()->json([
       'success' => false,
       'message' => $message,
       'code' => $code,
       'errors' => $errors,
   ], $code);
   ```

2. **Lista y muestra autores.** `GET /api/autores` devuelve todos. `GET /api/autores/{autor}` devuelve uno. Si el id no existe, responde 404.

3. **Crea un autor.** `POST /api/autores` exige `name` y `nationality`. Si falta un campo, responde con `error` y código 422.

4. **Actualiza un autor.** `PUT /api/autores/{autor}` modifica `name` y `nationality` y devuelve el autor actualizado.

5. **Elimina un autor.** `DELETE /api/autores/{autor}` borra el registro. Si todavía tiene libros, `restrictOnDelete` lo impide: captura ese fallo y responde con `error`, sin dejar la excepción sin controlar.

6. **CRUD de categorías.** Implementa listar, ver, crear, actualizar y eliminar en `/api/categorias`. `name` es obligatorio y no puede repetirse.

7. **Crea un libro.** `POST /api/libros` recibe `title`, `isbn`, `publication_year`, `quantity`, `author_id` y `category_id`. El `isbn` es único y los dos id deben existir.

8. **Muestra un libro con sus relaciones.** `GET /api/libros/{libro}` devuelve el libro junto con su autor y su categoría.

9. **Actualiza y elimina un libro.** `PUT /api/libros/{libro}` modifica sus campos. `DELETE /api/libros/{libro}` lo elimina. Al actualizar, el `isbn` puede mantenerse si es el del mismo libro.

10. **Libros de un autor.** `GET /api/autores/{autor}/libros` devuelve solo los libros de ese autor. Si el autor no existe, responde 404.
