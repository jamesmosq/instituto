<?php

namespace Database\Seeders;

use App\Models\Docente;
use App\Models\Sede;
use Illuminate\Database\Seeder;

/**
 * Paso 2: docentes. Necesitan que existan sedes (FK sede_id).
 */
class DocenteSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Sede::all() as $sede) {
            // for($sede) llena sede_id con el id de esa sede (lado belongsTo).
            Docente::factory()
                ->count(4)
                ->for($sede)
                ->create();
        }
    }
}
