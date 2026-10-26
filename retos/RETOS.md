# Retos en Tinker · Relaciones Eloquent

Abre la consola interactiva de Laravel:

```bash
php artisan tinker
```

Tinker reconoce los modelos por su nombre corto (`Estudiante`, `Curso`…). Si en tu equipo no los reconoce, escribe el nombre completo: `App\Models\Estudiante`.

**Regla de trabajo para cada reto:**

1. Antes de escribir el código, escribe en tu cuaderno el SQL que crees que se va a ejecutar.
2. Resuelve el reto en Tinker.
3. Cambia `->get()` / `->first()` / `->count()` por `->toRawSql()` y compara con lo que escribiste.

Las soluciones están en `SOLUCIONES.md`. Intenta cada reto antes de mirarlas.

---

## Nivel 1 · Uno a uno (Estudiante ↔ Carnet)

**R1.** Trae el primer estudiante que tenga carnet y muestra el código de su carnet.

**R2.** Trae el primer carnet y muestra los nombres del estudiante al que pertenece.

**R3.** ¿Cuántos estudiantes NO tienen carnet? (Deberían ser 5.)

> Pregunta para pensar: ¿por qué en `Estudiante` el método es `hasOne` y en `Carnet` es `belongsTo`? ¿En qué tabla está la columna `estudiante_id`?

---

## Nivel 2 · Uno a muchos (Sede → Docentes → Cursos)

**R4.** Trae el primer docente y lista los nombres de sus cursos.

**R5.** Del mismo docente, cuenta solo sus cursos **activos**. Hazlo sin traer todos los cursos a memoria.

> Pista: diferencia entre `$docente->cursos` (propiedad) y `$docente->cursos()` (método).

**R6.** Toma el primer curso y muestra el nombre de la **sede** donde se dicta. (No existe `sede_id` en `cursos`: tienes que encadenar dos relaciones.)

**R7.** Cuenta los cursos de cada sede usando la relación `cursos()` de `Sede`. Luego mira su `toRawSql()`: ¿qué tabla aparece en el `JOIN`?

---

## Nivel 3 · Muchos a muchos (Cursos ↔ Estudiantes, pivote con nota)

**R8.** Trae un estudiante que tenga cursos e imprime cada curso con su nota. Si la nota es `null`, muestra "sin nota".

**R9.** Calcula el promedio de notas de ese estudiante con una sola consulta.

**R10.** Matricula a ese estudiante en un curso activo en el que todavía NO esté.

**R11.** Ponle nota 4.8 en ese curso nuevo, sin borrar la matrícula.

> Pregunta para pensar: ¿por qué la `nota` no está ni en `cursos` ni en `estudiantes`?

---

## Nivel 4 · Consultas sobre relaciones

**R12.** ¿Qué docente dicta más cursos? Muestra su nombre y la cantidad, sin traer los cursos.

**R13.** ¿Cuántos cursos tienen más de 8 estudiantes matriculados?

**R14.** ¿Cuántos estudiantes van perdiendo al menos un curso (nota menor a 3.0)? Resuélvelo de dos formas: con `whereHas` y con `whereRelation`.

**R15.** Provoca a propósito el problema N+1: recorre `Curso::all()` y muestra el nombre del docente de cada uno. ¿Qué error aparece? Corrígelo y cuenta cuántas consultas se ejecutan.

> Pista para contar consultas: `DB::enableQueryLog();` antes y `count(DB::getQueryLog());` después.

**R16.** Trae todas las sedes con sus docentes y los cursos de cada docente, en **3 consultas en total**. Muestra cuántos cursos tiene cada sede.

**R17.** Trae un estudiante con solo los cursos que **ya tienen nota** cargados (carga anticipada con condición sobre la pivote).

**R18.** Encuentra el estudiante con el mejor promedio. El promedio debe llegar como un atributo llamado `promedio`.

---

## Reto final · Inspeccionar los modelos

Sal de Tinker y ejecuta:

```bash
php artisan model:show Estudiante
php artisan model:show Sede
```

Revisa la sección **Relations** de cada uno. ¿Coinciden con lo que hay en el diagrama de la guía?
