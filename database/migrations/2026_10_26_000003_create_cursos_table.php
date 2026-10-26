<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * TABLA 3 de 6: cursos
 *
 * Relación: Docente 1 ──── N Curso
 * La FK docente_id vive AQUÍ (lado "muchos"). Sin UNIQUE: muchos cursos
 * pueden repetir el mismo docente_id.
 *
 * SQL equivalente (MySQL):
 *   CREATE TABLE cursos (
 *       id         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 *       docente_id BIGINT UNSIGNED NOT NULL,
 *       codigo     VARCHAR(20)  NOT NULL UNIQUE,
 *       nombre     VARCHAR(120) NOT NULL,
 *       cupos      SMALLINT UNSIGNED NOT NULL DEFAULT 30,
 *       activo     TINYINT(1) NOT NULL DEFAULT 1,
 *       created_at TIMESTAMP NULL,
 *       updated_at TIMESTAMP NULL,
 *       FOREIGN KEY (docente_id) REFERENCES docentes(id) ON DELETE RESTRICT
 *   );
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('docente_id')->constrained()->restrictOnDelete();
            $table->string('codigo', 20)->unique();
            $table->string('nombre', 120);
            $table->unsignedSmallInteger('cupos')->default(30);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};
