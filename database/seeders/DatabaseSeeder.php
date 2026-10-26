<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * El ORDEN importa: no puedes insertar un docente si su sede no existe
     * (la FK sede_id lo impide). Primero las tablas sin FK, luego las que dependen.
     *
     *   sedes → docentes → cursos
     *   estudiantes → carnets
     *   cursos + estudiantes → curso_estudiante
     */
    public function run(): void
    {
        $this->call([
            SedeSeeder::class,
            DocenteSeeder::class,
            CursoSeeder::class,
            EstudianteSeeder::class,
            MatriculaSeeder::class,
        ]);
    }
}
