<?php


namespace Cly\CodeGenerator;


use Cly\CodeGenerator\Command\CleanModelCommand;
use Cly\CodeGenerator\Command\MakeModelCommand;
use Illuminate\Support\ServiceProvider;

class CodeGeneratorServiceProvider extends ServiceProvider
{
    public function boot()
    {
    }

    public function register()
    {
        if ($this->app->environment() == 'production') {
            return;
        }

        $this->mergeConfigFrom(__DIR__ . '/../config/code_generator.php', 'code_generator');

        $this->publishes([
            __DIR__ . '/../config/code_generator.php' => config_path('code_generator.php'),
        ]);

        $this->commands([
            CleanModelCommand::class,
            MakeModelCommand::class,
        ]);

        $this->app->singleton('code_generator', function () {
            return new CodeGenerator(config('code_generator'));
        });
    }
}
