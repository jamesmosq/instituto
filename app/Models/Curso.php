<?php

namespace App\Models;

use Database\Factories\CursoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Tabla: cursos  (Curso → cursos)
 *
 * Relaciones:
 *   - docente()     → pertenece a un docente  (Curso SÍ tiene la FK docente_id)
 *   - estudiantes() → N:M a través de la pivote curso_estudiante
 */
#[Fillable(['docente_id', 'codigo', 'nombre', 'cupos', 'activo'])]
class Curso extends Model
{
    /** @use HasFactory<CursoFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'cupos' => 'integer',
            'activo' => 'boolean',
        ];
    }

    /**
     * Un curso PERTENECE A un docente.
     *
     * SQL: select * from docentes where id = ? limit 1
     */
    public function docente(): BelongsTo
    {
        return $this->belongsTo(Docente::class);
    }

    /**
     * Un curso tiene MUCHOS estudiantes, y un estudiante tiene MUCHOS cursos.
     * En N:M los dos lados usan belongsToMany.
     *
     * Eloquent deduce la pivote: curso + estudiante (orden alfabético) → curso_estudiante
     *   - withPivot('nota'): sin esto, $estudiante->pivot->nota llega en null.
     *   - withTimestamps():  llena created_at / updated_at de la pivote.
     *
     * SQL: select estudiantes.*, curso_estudiante.curso_id, curso_estudiante.estudiante_id, ...
     *      from estudiantes
     *      inner join curso_estudiante on estudiantes.id = curso_estudiante.estudiante_id
     *      where curso_estudiante.curso_id = ?
     */
    public function estudiantes(): BelongsToMany
    {
        return $this->belongsToMany(Estudiante::class)
            ->withPivot('nota')
            ->withTimestamps();
    }
}
