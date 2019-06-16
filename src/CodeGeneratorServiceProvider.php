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
        $this->commands([
            CleanModelCommand::class,
            MakeModelCommand::class,
        ]);
    }
}
