<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Detector del problema N+1.
        // Fuera de producción, Laravel lanza LazyLoadingViolationException cuando
        // recorres varios modelos y accedes a una relación que NO cargaste con with().
        // Ejemplo que explota:   foreach (Curso::all() as $c) { $c->docente->nombres; }
        // Ejemplo correcto:      foreach (Curso::with('docente')->get() as $c) { ... }
        Model::preventLazyLoading(! $this->app->isProduction());
    }
}
