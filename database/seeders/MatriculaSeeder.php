<?php

namespace Database\Seeders;

use App\Models\Curso;
use App\Models\Estudiante;
use Illuminate\Database\Seeder;

/**
 * Paso 5: matrículas = filas de la tabla pivote curso_estudiante (N:M).
 * Necesitan que existan cursos Y estudiantes.
 */
class MatriculaSeeder extends Seeder
{
    public function run(): void
    {
        $cursosActivos = Curso::where('activo', true)->get();

        foreach (Estudiante::all() as $estudiante) {
            $cursos = $cursosActivos->random(min(fake()->numberBetween(3, 5), $cursosActivos->count()));

            // attach() acepta [curso_id => [columnas extra de la pivote]]
            // SQL: insert into curso_estudiante (curso_id, estudiante_id, nota, created_at, updated_at) values ...
            $estudiante->cursos()->attach(
                $cursos->mapWithKeys(fn (Curso $curso) => [
                    // 1 de cada 5 matrículas queda sin nota (curso en progreso)
                    $curso->id => ['nota' => fake()->boolean(80) ? fake()->randomFloat(1, 2, 5) : null],
                ])->all()
            );
        }
    }
}
