<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * TABLA 1 de 6: sedes
 *
 * Es la tabla "raíz": no depende de nadie, por eso se crea primero.
 * Una sede TIENE MUCHOS docentes (1:N) y, a través de ellos, muchos cursos.
 *
 * SQL equivalente (MySQL):
 *   CREATE TABLE sedes (
 *       id         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 *       nombre     VARCHAR(100) NOT NULL,
 *       ciudad     VARCHAR(80)  NOT NULL,
 *       direccion  VARCHAR(150) NULL,
 *       created_at TIMESTAMP NULL,
 *       updated_at TIMESTAMP NULL
 *   );
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sedes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('ciudad', 80);
            $table->string('direccion', 150)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sedes');
    }
};
