<?php

namespace Database\Factories;

use App\Models\Estudiante;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Estudiante>
 */
class EstudianteFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombres' => fake()->firstName(),
            'apellidos' => fake()->lastName().' '.fake()->lastName(),
            'documento' => fake()->unique()->numerify('##########'),
            'email' => fake()->unique()->safeEmail(),
            'fecha_nacimiento' => fake()->dateTimeBetween('-35 years', '-16 years')->format('Y-m-d'),
        ];
    }
}
