<?php

/*
| El seeder completo debe dejar la base con datos coherentes.
*/

use App\Models\Carnet;
use App\Models\Curso;
use App\Models\Docente;
use App\Models\Estudiante;
use App\Models\Sede;
use Illuminate\Support\Facades\DB;

test('el DatabaseSeeder llena las 6 tablas respetando las relaciones', function () {
    $this->seed();

    expect(Sede::count())->toBe(3)
        ->and(Docente::count())->toBe(12)          // 4 por sede
        ->and(Curso::count())->toBeGreaterThanOrEqual(24) // 2 a 4 por docente
        ->and(Estudiante::count())->toBe(65)
        ->and(Carnet::count())->toBe(60)            // 5 estudiantes quedan sin carnet
        ->and(DB::table('curso_estudiante')->count())->toBeGreaterThan(0);
});

test('todas las matriculas del seeder son de cursos activos', function () {
    $this->seed();

    expect(Curso::where('activo', false)->has('estudiantes')->count())->toBe(0);
});
