<?php

namespace Noouh\AutoModel;

use Illuminate\Support\ServiceProvider;

class AutoModelServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->commands([
            \Noouh\AutoModel\Console\GenerateModelsCommand::class,
        ]);

        // Publish stubs
        $this->publishes([
            __DIR__ . '/stubs/model.stub' => base_path('stubs/model.stub'),
            __DIR__ . '/stubs/migration.stub' => base_path('stubs/migration.stub'),
        ], 'stubs');
    }

    public function register()
    {
        // Register any bindings or singletons
    }
}
