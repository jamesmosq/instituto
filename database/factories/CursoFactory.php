<?php

namespace Database\Factories;

use App\Models\Curso;
use App\Models\Docente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Curso>
 */
class CursoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'docente_id' => Docente::factory(),
            'codigo' => fake()->unique()->bothify('CUR-####'),
            'nombre' => fake()->randomElement([
                'Fundamentos de programación',
                'Lógica de programación',
                'Bases de datos relacionales',
                'SQL avanzado',
                'Programación orientada a objetos',
                'Desarrollo web con PHP',
                'Laravel desde cero',
                'HTML y CSS',
                'JavaScript moderno',
                'Control de versiones con Git',
                'Análisis de requisitos',
                'Modelado UML',
                'Pruebas de software',
                'Redes de computadores',
                'Inglés técnico',
                'Ética profesional',
                'Matemáticas discretas',
                'Estadística básica',
            ]),
            'cupos' => fake()->numberBetween(20, 35),
            'activo' => fake()->boolean(85), // 85 % de los cursos quedan activos
        ];
    }

    /**
     * Estado: curso inactivo. Uso: Curso::factory()->inactivo()->create()
     */
    public function inactivo(): static
    {
        return $this->state(fn (array $attributes) => [
            'activo' => false,
        ]);
    }
}
