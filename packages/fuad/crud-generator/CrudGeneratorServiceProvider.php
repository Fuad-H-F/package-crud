<?php


namespace Packages\Fuad\CrudGenerator;

use Illuminate\Support\ServiceProvider;
use Packages\Fuad\CrudGenerator\Console\Commands\GenerateCrudCommand;

class CrudGeneratorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateCrudCommand::class,
            ]);
        }

        // Register bindings, services, configurations, etc.
        if (!$this->app->routesAreCached()) {
            require __DIR__ . '/routes.php';
        }

        // ... Other boot logic for your package
    }

    public function register()
    {
        
    }
}