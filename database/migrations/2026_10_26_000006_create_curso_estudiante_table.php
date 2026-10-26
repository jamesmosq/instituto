<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * TABLA 6 de 6: curso_estudiante (tabla PIVOTE)
 *
 * Relación: Curso N ──── M Estudiante
 * Ni cursos ni estudiantes pueden guardar la FK (necesitarían una lista de ids
 * en una sola celda). Por eso aparece esta tercera tabla: CADA FILA ES UNA MATRÍCULA.
 *
 * Nombre por convención de Eloquent: los dos modelos en singular, en orden
 * alfabético, unidos por "_":  curso < estudiante  →  curso_estudiante
 *
 * "nota" es un dato de la CONEXIÓN (la nota de ESE estudiante en ESE curso),
 * por eso no vive ni en cursos ni en estudiantes.
 *
 * SQL equivalente (MySQL):
 *   CREATE TABLE curso_estudiante (
 *       id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 *       curso_id      BIGINT UNSIGNED NOT NULL,
 *       estudiante_id BIGINT UNSIGNED NOT NULL,
 *       nota          DECIMAL(3,1) NULL,
 *       created_at    TIMESTAMP NULL,
 *       updated_at    TIMESTAMP NULL,
 *       UNIQUE (curso_id, estudiante_id),
 *       FOREIGN KEY (curso_id)      REFERENCES cursos(id)      ON DELETE CASCADE,
 *       FOREIGN KEY (estudiante_id) REFERENCES estudiantes(id) ON DELETE CASCADE
 *   );
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('curso_estudiante', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained()->cascadeOnDelete();
            $table->foreignId('estudiante_id')->constrained()->cascadeOnDelete();
            $table->decimal('nota', 3, 1)->nullable(); // escala colombiana 0.0 – 5.0
            $table->timestamps();

            // Un estudiante no puede matricularse dos veces en el mismo curso.
            $table->unique(['curso_id', 'estudiante_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curso_estudiante');
    }
};
