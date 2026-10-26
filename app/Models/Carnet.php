<?php

namespace App\Models;

use Database\Factories\CarnetFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Tabla: carnets  (Carnet → carnets)
 *
 * Relación:
 *   - estudiante() → pertenece a un estudiante (Carnet SÍ tiene la FK estudiante_id)
 */
#[Fillable(['estudiante_id', 'codigo', 'fecha_expedicion', 'fecha_vencimiento'])]
class Carnet extends Model
{
    /** @use HasFactory<CarnetFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'fecha_expedicion' => 'date',
            'fecha_vencimiento' => 'date',
        ];
    }

    /**
     * Un carnet PERTENECE A un estudiante.
     * Mismo método que en 1:N: lo que hace 1:1 a esta relación es el UNIQUE de la migración.
     *
     * SQL: select * from estudiantes where id = ? limit 1
     */
    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(Estudiante::class);
    }
}
