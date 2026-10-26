<?php

namespace App\Models;

use Database\Factories\SedeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * Tabla: sedes  (Laravel la deduce: Sede → sedes)
 *
 * Relaciones:
 *   - docentes() → 1:N            (la FK sede_id está en docentes)
 *   - cursos()   → "a través de"  (sedes → docentes → cursos)
 *
 * Equivalente clásico de #[Fillable]: protected $fillable = ['nombre', 'ciudad', 'direccion'];
 */
#[Fillable(['nombre', 'ciudad', 'direccion'])]
class Sede extends Model
{
    /** @use HasFactory<SedeFactory> */
    use HasFactory;

    /**
     * Una sede TIENE MUCHOS docentes.
     * Sede NO tiene la FK → hasMany. Eloquent busca docentes.sede_id
     *
     * SQL: select * from docentes where sede_id = ?
     */
    public function docentes(): HasMany
    {
        return $this->hasMany(Docente::class);
    }

    /**
     * Una sede tiene muchos cursos A TRAVÉS DE sus docentes.
     * cursos no tiene sede_id: Eloquent salta por la tabla docentes.
     * hasManyThrough(modelo final, modelo intermedio)
     *
     * SQL: select cursos.* from cursos
     *      inner join docentes on docentes.id = cursos.docente_id
     *      where docentes.sede_id = ?
     */
    public function cursos(): HasManyThrough
    {
        return $this->hasManyThrough(Curso::class, Docente::class);
    }
}
