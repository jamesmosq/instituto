<?php

/*
| Relación "a través de" — Sede hasManyThrough Curso (pasando por Docente)
| La tabla cursos NO tiene sede_id: Eloquent salta por docentes.
*/

use App\Models\Curso;
use App\Models\Docente;
use App\Models\Sede;

test('una sede ve los cursos de todos sus docentes (hasManyThrough)', function () {
    $sede = Sede::factory()->create();
    Docente::factory()->count(2)->for($sede)->has(Curso::factory()->count(3))->create();

    expect($sede->cursos)->toHaveCount(6);
});

test('hasManyThrough no mezcla cursos de otras sedes', function () {
    $sedeA = Sede::factory()->create();
    $sedeB = Sede::factory()->create();
    Docente::factory()->for($sedeA)->has(Curso::factory()->count(2))->create();
    Docente::factory()->for($sedeB)->has(Curso::factory()->count(5))->create();

    expect($sedeA->cursos)->toHaveCount(2)
        ->and($sedeB->cursos)->toHaveCount(5);
});

test('el SQL de hasManyThrough es un JOIN con la tabla intermedia', function () {
    $sede = Sede::factory()->create();

    $sql = $sede->cursos()->toRawSql();

    expect($sql)->toContain('inner join')
        ->and($sql)->toContain('docentes')
        ->and($sql)->toContain('sede_id');
});
