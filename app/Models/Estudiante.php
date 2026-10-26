<?php

namespace App\Models;

use Database\Factories\EstudianteFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Tabla: estudiantes  (Estudiante → estudiantes)
 *
 * Relaciones:
 *   - carnet() → 1:1 (la FK estudiante_id está en carnets, con UNIQUE)
 *   - cursos() → N:M a través de la pivote curso_estudiante
 */
#[Fillable(['nombres', 'apellidos', 'documento', 'email', 'fecha_nacimiento'])]
class Estudiante extends Model
{
    /** @use HasFactory<EstudianteFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'fecha_nacimiento' => 'date',
        ];
    }

    /**
     * Un estudiante TIENE UN carnet.
     * Estudiante NO tiene la FK → hasOne. Eloquent busca carnets.estudiante_id
     *
     * SQL: select * from carnets where estudiante_id = ? limit 1
     */
    public function carnet(): HasOne
    {
        return $this->hasOne(Carnet::class);
    }

    /**
     * El otro lado del N:M. Misma pivote, mismo método belongsToMany.
     *
     * SQL: select cursos.*, curso_estudiante.* ... from cursos
     *      inner join curso_estudiante on cursos.id = curso_estudiante.curso_id
     *      where curso_estudiante.estudiante_id = ?
     */
    public function cursos(): BelongsToMany
    {
        return $this->belongsToMany(Curso::class)
            ->withPivot('nota')
            ->withTimestamps();
    }
}
