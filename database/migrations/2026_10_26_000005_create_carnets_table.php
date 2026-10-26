<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * TABLA 5 de 6: carnets
 *
 * Relación: Estudiante 1 ──── 1 Carnet
 * La FK estudiante_id vive AQUÍ y lleva UNIQUE.
 * Ese UNIQUE es lo único que convierte un 1:N en un 1:1:
 * sin él, un estudiante podría tener varios carnets.
 *
 * SQL equivalente (MySQL):
 *   CREATE TABLE carnets (
 *       id                BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 *       estudiante_id     BIGINT UNSIGNED NOT NULL UNIQUE,
 *       codigo            VARCHAR(20) NOT NULL UNIQUE,
 *       fecha_expedicion  DATE NOT NULL,
 *       fecha_vencimiento DATE NOT NULL,
 *       created_at        TIMESTAMP NULL,
 *       updated_at        TIMESTAMP NULL,
 *       FOREIGN KEY (estudiante_id) REFERENCES estudiantes(id) ON DELETE CASCADE
 *   );
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carnets', function (Blueprint $table) {
            $table->id();

            // Los modificadores (unique) van ANTES de constrained().
            // cascadeOnDelete(): si se borra el estudiante, se borra su carnet.
            $table->foreignId('estudiante_id')->unique()->constrained()->cascadeOnDelete();

            $table->string('codigo', 20)->unique();
            $table->date('fecha_expedicion');
            $table->date('fecha_vencimiento');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carnets');
    }
};
