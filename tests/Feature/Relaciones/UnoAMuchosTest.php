<?php

/*
| Relación 1:N — Docente hasMany Curso / Curso belongsTo Docente
|               (y también Sede hasMany Docente)
| La FK docente_id está en cursos (el lado "muchos").
*/

use App\Models\Curso;
use App\Models\Docente;
use App\Models\Sede;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;

test('un docente tiene muchos cursos (hasMany)', function () {
    $docente = Docente::factory()->has(Curso::factory()->count(3))->create();

    expect($docente->cursos)->toBeInstanceOf(Collection::class)
        ->and($docente->cursos)->toHaveCount(3);
});

test('un curso pertenece a un docente (belongsTo)', function () {
    $curso = Curso::factory()->create();

    expect($curso->docente)->toBeInstanceOf(Docente::class)
        ->and($curso->docente->id)->toBe($curso->docente_id);
});

test('una sede tiene muchos docentes', function () {
    $sede = Sede::factory()->has(Docente::factory()->count(4))->create();

    expect($sede->docentes)->toHaveCount(4);
});

test('propiedad vs metodo: cursos() permite filtrar antes de consultar', function () {
    $docente = Docente::factory()->create();
    Curso::factory()->count(2)->for($docente)->create(['activo' => true]);
    Curso::factory()->inactivo()->for($docente)->create();

    // $docente->cursos   → todos (Collection)
    // $docente->cursos() → query builder: se puede seguir encadenando
    expect($docente->cursos)->toHaveCount(3)
        ->and($docente->cursos()->where('activo', true)->count())->toBe(2);
});

test('crear un curso desde el docente llena docente_id solo', function () {
    $docente = Docente::factory()->create();

    $curso = $docente->cursos()->create([
        'codigo' => 'CUR-0001',
        'nombre' => 'Laravel desde cero',
        'cupos' => 30,
    ]);

    expect($curso->docente_id)->toBe($docente->id);
});

test('associate cambia el docente de un curso (hay que llamar save)', function () {
    $curso = Curso::factory()->create();
    $nuevoDocente = Docente::factory()->create();

    $curso->docente()->associate($nuevoDocente);
    $curso->save();

    expect($curso->fresh()->docente_id)->toBe($nuevoDocente->id);
});

test('no se puede borrar un docente que tiene cursos (restrictOnDelete)', function () {
    $docente = Docente::factory()->has(Curso::factory())->create();

    expect(fn () => $docente->delete())->toThrow(QueryException::class);
});
