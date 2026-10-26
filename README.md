# Instituto · Relaciones SQL ↔ Eloquent (Fase 1)

Proyecto didáctico en **Laravel 13 + MySQL** para entender las relaciones de Eloquent **antes** de construir vistas. No tiene controladores ni Blade: se trabaja con migraciones, modelos, factories, seeders, **Tinker** y **tests con Pest**.

Acompaña la guía *"Relaciones SQL ↔ Eloquent: guía paso a paso"*. Usa el mismo ejemplo del instituto, sin la tabla polimórfica.

## Las 6 tablas y sus relaciones

```
 sedes ──1:N──► docentes ──1:N──► cursos ◄──┐
   │                                         │ N:M
   └──── hasManyThrough (cursos) ────────────┤ (pivote: curso_estudiante, con nota)
                                             │
 carnets ──1:1──► estudiantes ◄──────────────┘
```

| Tabla | Llave foránea | Relación que enseña |
| --- | --- | --- |
| `sedes` | — | `hasMany` docentes · `hasManyThrough` cursos |
| `docentes` | `sede_id` | `belongsTo` sede · `hasMany` cursos |
| `cursos` | `docente_id` | `belongsTo` docente · `belongsToMany` estudiantes |
| `estudiantes` | — | `hasOne` carnet · `belongsToMany` cursos |
| `carnets` | `estudiante_id` (UNIQUE) | `belongsTo` estudiante (1:1) |
| `curso_estudiante` | `curso_id`, `estudiante_id` | Tabla pivote N:M con dato extra (`nota`) |

**Regla de oro:** la tabla que tiene la columna `algo_id` es la que **pertenece** (`belongsTo`). La otra **tiene** (`hasOne` / `hasMany`).

## Requisitos

- PHP **8.3** o superior, con las extensiones `pdo_mysql` y `pdo_sqlite` activas (en XAMPP, revisa `php.ini`).
- Composer 2.
- MySQL 8 (o MariaDB 10.6+).

Composer instala **Pest 4** si tienes PHP 8.3, o **Pest 5** si tienes PHP 8.4 o superior. No tienes que elegir.

## Instalación paso a paso

**1. Crea la base de datos en MySQL** (phpMyAdmin, Workbench o consola):

```sql
CREATE DATABASE instituto CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

**2. Instala dependencias y configura el entorno:**

```bash
composer install
cp .env.example .env          # en Windows (cmd): copy .env.example .env
php artisan key:generate
```

**3. Revisa las credenciales** en `.env` (por defecto `root` sin contraseña, base `instituto`):

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=instituto
DB_USERNAME=root
DB_PASSWORD=
```

**4. Crea las tablas y llénalas con datos de prueba:**

```bash
php artisan migrate --seed
```

Quedan 3 sedes, 12 docentes, entre 24 y 48 cursos, 65 estudiantes (60 con carnet) y unas 260 matrículas con nota.

**5. Corre los tests:**

```bash
php artisan test
```

Deben pasar los 35. Los tests usan **SQLite en memoria** (configurado en `phpunit.xml`), así que no tocan tu base MySQL.

## Cómo estudiar el proyecto

Sigue este orden. Cada paso tiene su migración, su modelo y su archivo de tests.

| Paso | Qué leer | Qué ejecutar |
| --- | --- | --- |
| 1. Uno a uno | `create_carnets_table` · `Estudiante::carnet()` · `Carnet::estudiante()` | `php artisan test --filter=UnoAUno` |
| 2. Uno a muchos | `create_docentes_table`, `create_cursos_table` · `Docente::cursos()` · `Curso::docente()` | `php artisan test --filter=UnoAMuchos` |
| 3. Muchos a muchos | `create_curso_estudiante_table` · `Curso::estudiantes()` · `Estudiante::cursos()` | `php artisan test --filter=MuchosAMuchos` |
| 4. A través de | `Sede::cursos()` | `php artisan test --filter=ATravesDe` |
| 5. Consultas y N+1 | `AppServiceProvider` · `ConsultasTest` | `php artisan test --filter=Consultas` |
| 6. Practicar | `retos/RETOS.md` | `php artisan tinker` |

Cada migración trae en su comentario el `CREATE TABLE` equivalente en SQL, y cada método de relación trae el `SELECT` que Laravel ejecuta.

## Estructura (solo lo que importa en esta fase)

```
app/Models/                 Sede, Docente, Curso, Estudiante, Carnet
app/Providers/AppServiceProvider.php   preventLazyLoading (detector de N+1)
database/migrations/2026_10_26_*       las 6 tablas, en orden de dependencia
database/factories/         datos falsos en español (es_ES)
database/seeders/           un seeder por tabla, llamados en orden desde DatabaseSeeder
database/esquema-mysql.sql  el SQL exacto que Laravel ejecuta en MySQL (para comparar)
tests/Feature/Relaciones/   un archivo de tests por tipo de relación
tests/Feature/SeederTest.php
retos/RETOS.md              18 retos para Tinker
retos/SOLUCIONES.md         soluciones (para el instructor)
```

## Comandos útiles

```bash
php artisan migrate:fresh --seed     # borra todo y vuelve a crear y sembrar
php artisan tinker                   # consola para practicar
php artisan model:show Estudiante    # muestra columnas y relaciones de un modelo
php artisan db:table curso_estudiante  # muestra la estructura de una tabla
php artisan test --filter=pivote     # corre solo los tests cuyo nombre contenga "pivote"
```

## Trampas que este proyecto te muestra

- **El pluralizador de Laravel es en inglés.** `Curso → cursos` funciona, pero `Profesor → profesors` no. Por eso los modelos se llaman `Sede`, `Docente`, `Curso`, `Estudiante` y `Carnet`. Si usas otra palabra, define `#[Table('nombre_tabla')]` o `protected $table`.
- **`has()` en factories adivina el nombre de la relación en plural.** Para un `hasOne` hay que decírselo: `->has(Carnet::factory(), 'carnet')` (ver `EstudianteSeeder`).
- **Columnas de la pivote en consultas:** escríbelas con el nombre de la tabla, `curso_estudiante.nota`, para que no se confundan con columnas de `cursos` o `estudiantes`.
- **En MySQL la nota llega como texto** (`"4.5"`) porque es `DECIMAL`. Conviértela con `(float)` si vas a operar con ella.
- **El seeder tiene orden:** primero las tablas sin FK (`sedes`, `estudiantes`), luego las que dependen de ellas.

## Fase 2 (siguiente)

Sobre estos mismos modelos se agregan rutas, controladores y vistas Blade: listado de cursos por sede, ficha del estudiante con sus notas y formulario de matrícula con `sync()`. Como `preventLazyLoading` ya está activo, cualquier vista que olvide un `with()` va a fallar en desarrollo, y eso es intencional.

## Documentación oficial

- [Eloquent: Relationships](https://laravel.com/docs/13.x/eloquent-relationships)
- [Migrations · Foreign Key Constraints](https://laravel.com/docs/13.x/migrations#foreign-key-constraints)
- [Eloquent: Factories](https://laravel.com/docs/13.x/eloquent-factories)
- [Database Testing](https://laravel.com/docs/13.x/database-testing)
- [Pest · Installation](https://pestphp.com/docs/installation)
