<?php

namespace packages\fuad\crudgenerator\src\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class GenerateCrudCommand extends Command
{
    // ... Command properties, signature, description, etc.
    protected $signature = 'crud:generate {name}';
    protected $description = 'Generate a basic CRUD operation';

    public function handle()
    {
        $modelName = ucfirst($this->argument('name')); // Replace with your model name

        // Generate Controller
        $this->call('make:controller', ['name' => "{$modelName}Controller"]);

        // Generate Model
        $this->call('make:model', ['name' => $modelName]);

        // Generate Requests
        $this->call('make:request', ['name' => "{$modelName}Request"]);

        // Generate Views
        $this->generateViews($modelName);

        // Generate Migration
        $this->call('make:migration', ['name' => "create_{$modelName}s_table"]);
        $this->replaceMigrationFileContent($modelName);

        // Generate Routes
        $this->generateRoutes($modelName);

        $this->info('CRUD components generated successfully.');
    }

    protected function generateViews($modelName)
    {
        $viewPath = resource_path("views/{$modelName}");
        if (!File::exists($viewPath)) {
            File::makeDirectory($viewPath);
        }

        $viewFiles = ['index', 'create', 'edit', 'show'];
        foreach ($viewFiles as $viewFile) {
            $viewContent = // Load your view template content here
            File::put("{$viewPath}/{$viewFile}.blade.php", $viewContent);
        }
    }

    protected function replaceMigrationFileContent($modelName)
    {
        $migrationFiles = File::files(database_path('migrations'));

        foreach ($migrationFiles as $migrationFile) {
            $contents = File::get($migrationFile);
            if (strpos($contents, 'create_' . strtolower($modelName) . 's_table') !== false) {
                $contents = str_replace('your_table_name', strtolower($modelName) . 's', $contents);
                File::put($migrationFile, $contents);
                break;
            }
        }
    }

    protected function generateRoutes($modelName)
    {
        $routeContent = "
            // CRUD routes for {$modelName}
            Route::resource('{$modelName}', {$modelName}Controller::class);
        ";

        File::append(base_path('routes/web.php'), $routeContent);
    }
}