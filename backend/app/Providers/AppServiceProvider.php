<?php

namespace App\Providers;

use App\Support\TenantContext;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Singleton: uma única instância por requisição. Todos os pontos
        // que consultam o tenant atual — escopos do Eloquent hoje,
        // políticas de RLS a seguir — leem do mesmo objeto.
        $this->app->singleton(TenantContext::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
