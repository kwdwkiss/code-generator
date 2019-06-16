<?php


namespace Cly\CodeGenerator\Command;

use Cly\CodeGenerator\CodeGenerator;
use Illuminate\Console\Command;

class MakeModelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cly:make 
    {name} 
    {title?} 
    {--d|migration}  
    {--m|model}
    {--r|resource}
    {--c|controller}
    {--o|route}
    {--a|admin}
    {--i|index}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');
        $title = $this->argument('title') ?: '';

        $migration = $this->option('migration');
        $model = $this->option('model');
        $resource = $this->option('resource');
        $controller = $this->option('controller');
        $route = $this->option('route');
        $admin = $this->option('admin');
        $index = $this->option('index');

        $actions = compact(
            'migration',
            'model',
            'resource',
            'controller',
            'route',
            'admin',
            'index'
        );

        if (empty(array_filter($actions))) {
            $actions = array_map(function () {
                return true;
            }, $actions);
        }

        $config = $actions + compact(
                'name',
                'title'
            );

        $generator = new CodeGenerator($config);

        $this->doAction($generator);
    }

    protected function doAction($generator)
    {
        $generator->make();
    }
}
