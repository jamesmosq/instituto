<?php

namespace Database\Seeders;

use App\Models\Carnet;
use App\Models\Estudiante;
use Illuminate\Database\Seeder;

/**
 * Paso 4: estudiantes, cada uno con su carnet (1:1).
 */
class EstudianteSeeder extends Seeder
{
    public function run(): void
    {
        // has(..., 'carnet'): crea el carnet de cada estudiante.
        //
        // OJO: hay que decirle el nombre de la relación ('carnet').
        // Sin él, Laravel lo deduce en PLURAL a partir del modelo (Carnet → 'carnets')
        // y fallaría, porque nuestro método se llama carnet() en singular (es un hasOne).
        Estudiante::factory()
            ->count(60)
            ->has(Carnet::factory(), 'carnet')
            ->create();

        // 5 estudiantes SIN carnet, para practicar doesntHave('carnet') y withDefault().
        Estudiante::factory()->count(5)->create();
    }
}
