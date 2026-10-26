<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Fase 1: sin rutas ni vistas
|--------------------------------------------------------------------------
| En esta fase solo trabajamos modelos, migraciones y relaciones.
| Se practica con Tinker (php artisan tinker) y con los tests (php artisan test).
|
| Fase 2: aquí irán las rutas de los controladores y las vistas Blade.
| Ejemplo de lo que vendrá:
|   Route::resource('cursos', CursoController::class);
*/

Route::get('/', function () {
    return response(
        'Instituto · Fase 1 (relaciones Eloquent). Usa "php artisan tinker" o "php artisan test".',
        200,
        ['Content-Type' => 'text/plain; charset=UTF-8']
    );
});
