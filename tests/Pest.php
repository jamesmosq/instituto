<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Configuración de Pest
|--------------------------------------------------------------------------
| Todos los tests de tests/Feature:
|   - extienden Tests\TestCase (arrancan Laravel)
|   - usan RefreshDatabase: cada test empieza con la base de datos limpia
|
| Los tests usan SQLite en memoria (ver phpunit.xml), así que no tocan tu base MySQL.
*/

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');
