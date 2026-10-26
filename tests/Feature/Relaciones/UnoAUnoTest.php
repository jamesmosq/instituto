<?php

/*
| Relación 1:1 — Estudiante hasOne Carnet / Carnet belongsTo Estudiante
| La FK estudiante_id está en carnets y tiene UNIQUE.
*/

use App\Models\Carnet;
use App\Models\Estudiante;
use Illuminate\Database\QueryException;

test('un estudiante tiene un carnet (hasOne)', function () {
    $estudiante = Estudiante::factory()->create();
    $carnet = Carnet::factory()->for($estudiante)->create();

    expect($estudiante->carnet)->toBeInstanceOf(Carnet::class)
        ->and($estudiante->carnet->id)->toBe($carnet->id);
});

test('un carnet pertenece a un estudiante (belongsTo)', function () {
    $carnet = Carnet::factory()->create();

    expect($carnet->estudiante)->toBeInstanceOf(Estudiante::class)
        ->and($carnet->estudiante->id)->toBe($carnet->estudiante_id);
});

test('crear el carnet desde el estudiante llena estudiante_id solo', function () {
    $estudiante = Estudiante::factory()->create();

    $carnet = $estudiante->carnet()->create([
        'codigo' => 'EST-000001',
        'fecha_expedicion' => '2026-02-01',
        'fecha_vencimiento' => '2027-02-01',
    ]);

    expect($carnet->estudiante_id)->toBe($estudiante->id);
});

test('el UNIQUE impide un segundo carnet para el mismo estudiante', function () {
    $estudiante = Estudiante::factory()->create();
    Carnet::factory()->for($estudiante)->create();

    expect(fn () => Carnet::factory()->for($estudiante)->create())
        ->toThrow(QueryException::class);
});

test('un estudiante sin carnet devuelve null', function () {
    $estudiante = Estudiante::factory()->create();

    expect($estudiante->carnet)->toBeNull();
});

test('al borrar el estudiante se borra su carnet (cascadeOnDelete)', function () {
    $carnet = Carnet::factory()->create();

    $carnet->estudiante->delete();

    expect(Carnet::find($carnet->id))->toBeNull();
});
