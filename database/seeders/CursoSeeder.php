<?php

namespace Database\Seeders;

use App\Models\Curso;
use App\Models\Docente;
use Illuminate\Database\Seeder;

/**
 * Paso 3: cursos. Necesitan que existan docentes (FK docente_id).
 */
class CursoSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Docente::all() as $docente) {
            // for($docente) llena docente_id (lado belongsTo).
            // Equivale a crear desde el lado hasMany: $docente->cursos()->create([...])
            Curso::factory()
                ->count(fake()->numberBetween(2, 4))
                ->for($docente)
                ->create();
        }
    }
}
