<?php

/*
| Relación N:M — Curso belongsToMany Estudiante / Estudiante belongsToMany Curso
| Las FK viven en la tabla pivote curso_estudiante, que además guarda la nota.
*/

use App\Models\Curso;
use App\Models\Estudiante;
use Illuminate\Database\QueryException;

test('los dos lados usan belongsToMany y ven la misma matricula', function () {
    $curso = Curso::factory()->create();
    $estudiante = Estudiante::factory()->create();

    $estudiante->cursos()->attach($curso->id);

    expect($estudiante->cursos)->toHaveCount(1)
        ->and($curso->estudiantes)->toHaveCount(1)
        ->and($curso->estudiantes->first()->id)->toBe($estudiante->id);
});

test('attach guarda la nota en la pivote y withPivot permite leerla', function () {
    $curso = Curso::factory()->create();
    $estudiante = Estudiante::factory()->create();

    $estudiante->cursos()->attach($curso->id, ['nota' => 4.5]);

    $matriculado = $estudiante->cursos()->first();

    expect((float) $matriculado->pivot->nota)->toBe(4.5)
        ->and($matriculado->pivot->created_at)->not->toBeNull(); // withTimestamps()
});

test('updateExistingPivot cambia la nota de una matricula', function () {
    $curso = Curso::factory()->create();
    $estudiante = Estudiante::factory()->create();
    $estudiante->cursos()->attach($curso->id, ['nota' => 2.8]);

    $estudiante->cursos()->updateExistingPivot($curso->id, ['nota' => 3.6]);

    expect((float) $estudiante->cursos()->first()->pivot->nota)->toBe(3.6);
});

test('sync deja exactamente los cursos indicados', function () {
    $estudiante = Estudiante::factory()->create();
    [$a, $b, $c] = Curso::factory()->count(3)->create();

    $estudiante->cursos()->attach([$a->id, $b->id]);
    $estudiante->cursos()->sync([$b->id, $c->id]);

    expect($estudiante->cursos()->pluck('cursos.id')->sort()->values()->all())
        ->toBe(collect([$b->id, $c->id])->sort()->values()->all());
});

test('syncWithoutDetaching agrega sin quitar los existentes', function () {
    $estudiante = Estudiante::factory()->create();
    [$a, $b] = Curso::factory()->count(2)->create();

    $estudiante->cursos()->attach($a->id);
    $estudiante->cursos()->syncWithoutDetaching([$b->id]);

    expect($estudiante->cursos()->count())->toBe(2);
});

test('detach quita la matricula pero no borra ni el curso ni el estudiante', function () {
    $curso = Curso::factory()->create();
    $estudiante = Estudiante::factory()->create();
    $estudiante->cursos()->attach($curso->id);

    $estudiante->cursos()->detach($curso->id);

    expect($estudiante->cursos()->count())->toBe(0)
        ->and(Curso::find($curso->id))->not->toBeNull()
        ->and(Estudiante::find($estudiante->id))->not->toBeNull();
});

test('toggle pone lo que no esta y quita lo que esta', function () {
    $estudiante = Estudiante::factory()->create();
    [$a, $b] = Curso::factory()->count(2)->create();
    $estudiante->cursos()->attach($a->id);

    $estudiante->cursos()->toggle([$a->id, $b->id]);

    expect($estudiante->cursos()->pluck('cursos.id')->all())->toBe([$b->id]);
});

test('el UNIQUE de la pivote impide matricular dos veces al mismo estudiante', function () {
    $curso = Curso::factory()->create();
    $estudiante = Estudiante::factory()->create();
    $estudiante->cursos()->attach($curso->id);

    expect(fn () => $estudiante->cursos()->attach($curso->id))
        ->toThrow(QueryException::class);
});

test('wherePivot filtra por columnas de la pivote', function () {
    $estudiante = Estudiante::factory()->create();
    [$a, $b, $c] = Curso::factory()->count(3)->create();

    $estudiante->cursos()->attach([
        $a->id => ['nota' => 4.0],
        $b->id => ['nota' => 2.5],
        $c->id => ['nota' => 3.0],
    ]);

    expect($estudiante->cursos()->wherePivot('nota', '>=', 3)->count())->toBe(2);
});
