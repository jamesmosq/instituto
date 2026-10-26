<?php

/*
| Consultar relaciones: has / whereHas / doesntHave / withCount / with (N+1)
*/

use App\Models\Carnet;
use App\Models\Curso;
use App\Models\Docente;
use App\Models\Estudiante;
use Illuminate\Database\LazyLoadingViolationException;
use Illuminate\Support\Facades\DB;

test('has trae solo los docentes que tienen cursos', function () {
    Docente::factory()->has(Curso::factory()->count(2))->create();
    Docente::factory()->create(); // sin cursos

    expect(Docente::has('cursos')->count())->toBe(1)
        ->and(Docente::doesntHave('cursos')->count())->toBe(1);
});

test('whereHas filtra por una condicion dentro de la relacion', function () {
    $conCursoGrande = Docente::factory()->create();
    Curso::factory()->for($conCursoGrande)->create(['cupos' => 35]);

    $soloCursosPequenos = Docente::factory()->create();
    Curso::factory()->for($soloCursosPequenos)->create(['cupos' => 20]);

    $resultado = Docente::whereHas('cursos', fn ($q) => $q->where('cupos', '>', 30))->pluck('id');

    expect($resultado->all())->toBe([$conCursoGrande->id]);
});

test('whereHas sobre N:M puede filtrar por columnas de la pivote', function () {
    $curso = Curso::factory()->create();
    $reprobando = Estudiante::factory()->create();
    $aprobando = Estudiante::factory()->create();
    $reprobando->cursos()->attach($curso->id, ['nota' => 2.1]);
    $aprobando->cursos()->attach($curso->id, ['nota' => 4.3]);

    $ids = Estudiante::whereHas('cursos', fn ($q) => $q->where('curso_estudiante.nota', '<', 3))->pluck('id');

    expect($ids->all())->toBe([$reprobando->id]);
});

test('doesntHave encuentra estudiantes sin carnet', function () {
    Carnet::factory()->count(2)->create();       // 2 estudiantes con carnet
    Estudiante::factory()->count(3)->create();   // 3 sin carnet

    expect(Estudiante::doesntHave('carnet')->count())->toBe(3);
});

test('withCount cuenta sin traer los registros relacionados', function () {
    Docente::factory()->has(Curso::factory()->count(4))->create();

    $docente = Docente::withCount('cursos')->first();

    expect($docente->cursos_count)->toBe(4)
        ->and($docente->relationLoaded('cursos'))->toBeFalse();
});

test('withSum suma una columna de la relacion', function () {
    $docente = Docente::factory()->create();
    Curso::factory()->for($docente)->create(['cupos' => 20]);
    Curso::factory()->for($docente)->create(['cupos' => 25]);

    expect((int) Docente::withSum('cursos', 'cupos')->first()->cursos_sum_cupos)->toBe(45);
});

test('sin with() Laravel detecta el problema N+1 (preventLazyLoading)', function () {
    Curso::factory()->count(3)->create();

    expect(function () {
        foreach (Curso::all() as $curso) {
            $curso->docente->nombres; // carga perezosa dentro de un ciclo
        }
    })->toThrow(LazyLoadingViolationException::class);
});

test('con with() se cargan las relaciones en 2 consultas, no en N+1', function () {
    Curso::factory()->count(10)->create();

    DB::enableQueryLog();

    foreach (Curso::with('docente')->get() as $curso) {
        $curso->docente->nombres;
    }

    expect(DB::getQueryLog())->toHaveCount(2);
});
