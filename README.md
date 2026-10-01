# biblioteca-api

Proyecto base en Laravel para la evaluación de la API de biblioteca. Incluye únicamente la capa de datos: modelos, migraciones, factories y seeders. Las rutas, los controladores y el trait de respuestas los implementan los estudiantes.

## Requisitos

- PHP 8.2 o superior
- Composer
- Extensión PDO SQLite

La base de datos es SQLite. El archivo es `database/database.sqlite`.

## Instalación

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```

`APP_FAKER_LOCALE=es_ES` ya está definido en `.env.example`, así que Faker genera nombres, países y textos en español.

La API queda en `http://localhost:8000/api`. El archivo `routes/api.php` ya existe (se creó con `php artisan install:api`) y solo trae la ruta de ejemplo de Sanctum. Ahí se agregan las rutas de la tarea.

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

## Tarea

1. Rutas en `routes/api.php`:
   - `Route::apiResource` para `autores`, `categorias` y `libros`.
   - `GET /api/autores/{autor}/libros`, que devuelva los libros de ese autor.
2. Controladores con `index`, `store`, `show`, `update` y `destroy`. La ruta anidada necesita una acción adicional, por ejemplo `libros` en el controlador de autores.
3. Trait `App\Traits\ApiResponse` (la carpeta `app/Traits` ya existe) con:
   - `success($data, $message = 'OK', $code = 200)`
   - `error($message, $code = 400, $errors = null)`

   Ambos métodos devuelven una respuesta JSON. Una forma coherente:

   ```php
   // success
   return response()->json([
       'success' => true,
       'message' => $message,
       'data' => $data,
   ], $code);

   // error ($errors solo si no es null)
   return response()->json([
       'success' => false,
       'message' => $message,
       'errors' => $errors,
   ], $code);
   ```

4. Usar el trait en todos los controladores.

No hace falta crear Form Requests ni API Resources para esta base: la validación y el formato de salida pueden vivir en los controladores, salvo que el enunciado de la evaluación pida lo contrario.
