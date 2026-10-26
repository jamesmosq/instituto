<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * TABLA 2 de 6: docentes
 *
 * Relación: Sede 1 ──── N Docente
 * La llave foránea (sede_id) vive AQUÍ, en el lado "muchos".
 * Regla de oro: la tabla que tiene la FK es la que "pertenece" (belongsTo).
 *
 * SQL equivalente (MySQL):
 *   CREATE TABLE docentes (
 *       id           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 *       sede_id      BIGINT UNSIGNED NOT NULL,
 *       nombres      VARCHAR(80)  NOT NULL,
 *       apellidos    VARCHAR(80)  NOT NULL,
 *       documento    VARCHAR(20)  NOT NULL UNIQUE,
 *       email        VARCHAR(150) NOT NULL UNIQUE,
 *       especialidad VARCHAR(100) NOT NULL,
 *       created_at   TIMESTAMP NULL,
 *       updated_at   TIMESTAMP NULL,
 *       FOREIGN KEY (sede_id) REFERENCES sedes(id) ON DELETE RESTRICT
 *   );
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('docentes', function (Blueprint $table) {
            $table->id();

            // foreignId('sede_id') crea un BIGINT UNSIGNED.
            // constrained() deduce la tabla por convención: sede_id → sedes.id
            // restrictOnDelete(): no deja borrar una sede que todavía tenga docentes.
            $table->foreignId('sede_id')->constrained()->restrictOnDelete();

            $table->string('nombres', 80);
            $table->string('apellidos', 80);
            $table->string('documento', 20)->unique();
            $table->string('email', 150)->unique();
            $table->string('especialidad', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('docentes');
    }
};
