<?php

namespace App\Models;

use Database\Factories\DocenteFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Tabla: docentes  (Docente → docentes)
 *
 * Relaciones:
 *   - sede()   → pertenece a una sede  (Docente SÍ tiene la FK sede_id)
 *   - cursos() → tiene muchos cursos   (la FK docente_id está en cursos)
 */
#[Fillable(['sede_id', 'nombres', 'apellidos', 'documento', 'email', 'especialidad'])]
class Docente extends Model
{
    /** @use HasFactory<DocenteFactory> */
    use HasFactory;

    /**
     * Un docente PERTENECE A una sede.
     * Docente SÍ tiene la FK → belongsTo.
     * Eloquent arma la FK con el NOMBRE DEL MÉTODO + "_id": sede() → sede_id
     *
     * SQL: select * from sedes where id = ? limit 1
     */
    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class);
    }

    /**
     * Un docente TIENE MUCHOS cursos.
     * Eloquent arma la FK con el NOMBRE DEL MODELO + "_id": Docente → docente_id
     *
     * SQL: select * from cursos where docente_id = ?
     */
    public function cursos(): HasMany
    {
        return $this->hasMany(Curso::class);
    }
}
