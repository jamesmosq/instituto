<?php

namespace Database\Factories;

use App\Models\Sede;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Sede>
 */
class SedeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => 'Sede '.fake()->randomElement(['Centro', 'Norte', 'Sur', 'Occidente', 'Oriente']),
            'ciudad' => fake()->randomElement(['Medellín', 'Envigado', 'Itagüí', 'Bello', 'Sabaneta']),
            'direccion' => fake()->randomElement(['Calle', 'Carrera', 'Diagonal', 'Transversal'])
                .' '.fake()->numberBetween(1, 120).' # '.fake()->numberBetween(1, 99).'-'.fake()->numberBetween(1, 99),
        ];
    }
}
