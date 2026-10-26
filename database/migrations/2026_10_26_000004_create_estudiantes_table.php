<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * TABLA 4 de 6: estudiantes
 *
 * No tiene ninguna FK: igual que sedes, es una tabla "independiente".
 * Se relaciona con:
 *   - carnets          → 1:1 (la FK está en carnets)
 *   - curso_estudiante → N:M con cursos (la FK está en la tabla pivote)
 *
 * SQL equivalente (MySQL):
 *   CREATE TABLE estudiantes (
 *       id               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 *       nombres          VARCHAR(80)  NOT NULL,
 *       apellidos        VARCHAR(80)  NOT NULL,
 *       documento        VARCHAR(20)  NOT NULL UNIQUE,
 *       email            VARCHAR(150) NOT NULL UNIQUE,
 *       fecha_nacimiento DATE NULL,
 *       created_at       TIMESTAMP NULL,
 *       updated_at       TIMESTAMP NULL
 *   );
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();
            $table->string('nombres', 80);
            $table->string('apellidos', 80);
            $table->string('documento', 20)->unique();
            $table->string('email', 150)->unique();
            $table->date('fecha_nacimiento')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estudiantes');
    }
};
