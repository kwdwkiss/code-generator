<?php


namespace Cly\CodeGenerator\Command;


class CleanModelCommand extends MakeModelCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cly:clean 
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
        parent::handle();
    }

    protected function doAction($generator)
    {
        $generator->clean();
    }
}
