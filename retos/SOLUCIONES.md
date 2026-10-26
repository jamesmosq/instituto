# Soluciones de los retos

Hay más de una forma correcta de resolver cada reto. Estas son las más directas. Los datos son aleatorios, así que tus resultados no serán iguales a los de un compañero.

## Nivel 1 · Uno a uno

```php
// R1
$e = Estudiante::has('carnet')->first();
$e->carnet->codigo;
// select * from carnets where estudiante_id = ? limit 1

// R2
Carnet::first()->estudiante->nombres;
// select * from estudiantes where id = ? limit 1

// R3
Estudiante::doesntHave('carnet')->count();   // 5
// select count(*) from estudiantes where not exists (select * from carnets where estudiantes.id = carnets.estudiante_id)
```

La columna `estudiante_id` está en `carnets`. Quien tiene la FK **pertenece** (`belongsTo`); el otro lado **tiene** (`hasOne`).

## Nivel 2 · Uno a muchos

```php
// R4
$d = Docente::first();
$d->cursos->pluck('nombre');

// R5 — con el MÉTODO: el filtro y el conteo los hace MySQL
$d->cursos()->where('activo', true)->count();
// select count(*) from cursos where docente_id = ? and activo = 1

// R6 — Curso → docente (belongsTo) → sede (belongsTo)
Curso::first()->docente->sede->nombre;

// R7
Sede::first()->cursos()->count();
Sede::first()->cursos()->toRawSql();
// select * from cursos inner join docentes on docentes.id = cursos.docente_id where docentes.sede_id = 1

// Todas las sedes con su conteo, en una sola consulta:
Sede::withCount('cursos')->get()->pluck('cursos_count', 'nombre');
```

## Nivel 3 · Muchos a muchos

```php
// R8
$e = Estudiante::has('cursos')->first();
foreach ($e->cursos as $c) {
    echo $c->nombre.' → '.($c->pivot->nota ?? 'sin nota').PHP_EOL;
}

// R9 — la columna de la pivote se escribe con el nombre de la tabla
$e->cursos()->avg('curso_estudiante.nota');

// R10 — un curso activo donde NO esté matriculado
$nuevo = Curso::where('activo', true)
    ->whereDoesntHave('estudiantes', fn ($q) => $q->where('estudiantes.id', $e->id))
    ->first();
$e->cursos()->attach($nuevo->id);
// insert into curso_estudiante (curso_id, estudiante_id, created_at, updated_at) values (...)

// R11
$e->cursos()->updateExistingPivot($nuevo->id, ['nota' => 4.8]);
// update curso_estudiante set nota = 4.8, updated_at = ... where estudiante_id = ? and curso_id in (?)
```

La nota es un dato de la **matrícula** (de ese estudiante en ese curso). Por eso vive en la pivote.

## Nivel 4 · Consultas sobre relaciones

```php
// R12
$top = Docente::withCount('cursos')->orderByDesc('cursos_count')->first();
"{$top->nombres}: {$top->cursos_count}";

// R13
Curso::has('estudiantes', '>', 8)->count();

// R14
Estudiante::whereHas('cursos', fn ($q) => $q->where('curso_estudiante.nota', '<', 3))->count();
Estudiante::whereRelation('cursos', 'curso_estudiante.nota', '<', 3)->count();

// R15 — esto lanza LazyLoadingViolationException (activado en AppServiceProvider)
foreach (Curso::all() as $c) { echo $c->docente->nombres.PHP_EOL; }

// Corregido:
DB::enableQueryLog();
foreach (Curso::with('docente')->get() as $c) { echo $c->docente->nombres.PHP_EOL; }
count(DB::getQueryLog());   // 2

// R16 — 3 consultas: sedes, docentes, cursos
DB::flushQueryLog();
$sedes = Sede::with('docentes.cursos')->get();
foreach ($sedes as $s) {
    echo $s->nombre.': '.$s->docentes->sum(fn ($d) => $d->cursos->count()).PHP_EOL;
}
count(DB::getQueryLog());   // 3

// R17
$e = Estudiante::with(['cursos' => fn ($q) => $q->wherePivotNotNull('nota')])->first();
$e->cursos;

// R18 — "cursos as promedio" le pone nombre al atributo
$mejor = Estudiante::withAvg('cursos as promedio', 'curso_estudiante.nota')
    ->orderByDesc('promedio')
    ->first();
"{$mejor->nombres}: ".round($mejor->promedio, 2);
```

> Sin el alias, el atributo se llamaría `cursos_avg_curso_estudiantenota`, que es difícil de leer.
