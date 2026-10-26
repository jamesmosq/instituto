<?php

namespace Database\Factories;

use App\Models\Carnet;
use App\Models\Estudiante;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Carnet>
 */
class CarnetFactory extends Factory
{
    public function definition(): array
    {
        $expedicion = fake()->dateTimeBetween('-1 year', 'now');

        return [
            'estudiante_id' => Estudiante::factory(),
            'codigo' => fake()->unique()->bothify('EST-######'),
            'fecha_expedicion' => $expedicion->format('Y-m-d'),
            'fecha_vencimiento' => (clone $expedicion)->modify('+1 year')->format('Y-m-d'),
        ];
    }
}
