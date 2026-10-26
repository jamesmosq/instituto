<?php

namespace Database\Seeders;

use App\Models\Sede;
use Illuminate\Database\Seeder;

/**
 * Paso 1: sedes. No dependen de nadie, por eso van primero.
 */
class SedeSeeder extends Seeder
{
    public function run(): void
    {
        // Datos fijos con create(): así todos tienen las mismas 3 sedes.
        Sede::create(['nombre' => 'Sede Centro', 'ciudad' => 'Medellín', 'direccion' => 'Carrera 50 # 52-30']);
        Sede::create(['nombre' => 'Sede Sur', 'ciudad' => 'Envigado', 'direccion' => 'Calle 38 Sur # 43-20']);
        Sede::create(['nombre' => 'Sede Itagüí', 'ciudad' => 'Itagüí', 'direccion' => 'Carrera 51 # 50-15']);
    }
}
