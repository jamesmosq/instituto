<?php

namespace Database\Factories;

use App\Models\Docente;
use App\Models\Sede;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Docente>
 */
class DocenteFactory extends Factory
{
    public function definition(): array
    {
        return [
            // Si no le pasas una sede, la factory crea una nueva.
            // En el seeder usamos ->for($sede) para reutilizar las existentes.
            'sede_id' => Sede::factory(),

            'nombres' => fake()->firstName(),
            'apellidos' => fake()->lastName().' '.fake()->lastName(),
            'documento' => fake()->unique()->numerify('##########'),
            'email' => fake()->unique()->safeEmail(),
            'especialidad' => fake()->randomElement([
                'Desarrollo de software',
                'Bases de datos',
                'Redes y telecomunicaciones',
                'Diseño web',
                'Matemáticas',
                'Inglés técnico',
                'Seguridad y salud en el trabajo',
            ]),
        ];
    }
}
