<?php


namespace Cly\CodeGenerator;


use Illuminate\Support\Str;

class CodeGenerator
{
    protected $config = [];

    protected $name = '';

    protected $title = '';

    protected $migration = true;

    protected $model = true;

    protected $resource = true;

    protected $controller = true;

    protected $route = true;

    protected $index = true;

    protected $admin = true;

    protected $namespace = 'Modules\Common\Entities';

    protected $modelStubPath = __DIR__ . '/stubs/model.stub';

    protected $modelDistPath = 'Modules/Common/Entities';

    protected $migrationStubPath = __DIR__ . '/stubs/migration.stub';

    protected $migrationDistPath = 'database/migrations';

    protected $resourceStubPath = __DIR__ . '/stubs/resource.stub';

    protected $resourceDistPath = 'Modules/Common/Transformers';

    protected $adminControllerStubPath = __DIR__ . '/stubs/admin_controller.stub';

    protected $adminControllerDistPath = 'Modules/Admin/Http/Controllers';

    protected $adminRouteStubPath = __DIR__ . '/stubs/admin_route.stub';

    protected $adminRouteDistPath = 'Modules/Admin/Routes/web.php';

    protected $indexControllerStubPath = __DIR__ . '/stubs/index_controller.stub';

    protected $indexControllerDistPath = 'Modules/Index/Http/Controllers';

    protected $indexRouteStubPath = __DIR__ . '/stubs/index_route.stub';

    protected $indexRouteDistPath = 'Modules/Index/Routes/web.php';

    public function __construct($config = [])
    {
        $this->config = $config;

        foreach ($config as $key => $value) {
            $this->$key = $value;
        }

        if (empty($this->name)) {
            throw new \Exception('name is empty');
        }
        $this->name = Str::studly($this->name);
    }

    public function make()
    {
        $this->migration && $this->makeMigration();
        $this->model && $this->makeModel();
        $this->resource && $this->makeResource();
        $this->admin && $this->controller && $this->makeAdminController();
        $this->admin && $this->route && $this->makeAdminRoute();
        $this->index && $this->controller && $this->makeIndexController();
        $this->index && $this->route && $this->makeIndexRoute();
    }

    public function makeMigration()
    {
        $migrationClass = 'Create' . Str::plural($this->name) . 'Table';
        $table = Str::snake(Str::plural($this->name));

        $stub = file_get_contents($this->migrationStubPath);
        $stub = str_replace([
            '{$migrationClass}',
            '{$table}',
        ], [
            $migrationClass,
            $table,
        ], $stub);

        $filename = date('Y_m_d_His') . '_' . Str::snake($migrationClass) . '.php';
        file_put_contents($this->migrationDistPath . '/' . $filename, $stub);
    }

    public function makeModel()
    {
        $namespace = $this->namespace;
        $model = $this->name;

        $stub = file_get_contents($this->modelStubPath);
        $stub = str_replace([
            '{$namespace}',
            '{$model}',
        ], [
            $namespace,
            $model,
        ], $stub);

        $filename = $model . '.php';
        file_put_contents($this->modelDistPath . '/' . $filename, $stub);
    }

    public function makeResource()
    {
        $resourceClass = $this->name . 'Resource';

        $stub = file_get_contents($this->resourceStubPath);
        $stub = str_replace([
            '{$resourceClass}',
        ], [
            $resourceClass,
        ], $stub);

        $filename = $resourceClass . '.php';
        file_put_contents($this->resourceDistPath . '/' . $filename, $stub);
    }

    public function makeAdminController()
    {
        $class = $this->name;
        $controllerClass = $this->name . 'Controller';

        $stub = file_get_contents($this->adminControllerStubPath);
        $stub = str_replace([
            'User',
        ], [
            $class,
        ], $stub);

        $filename = $controllerClass . '.php';
        file_put_contents($this->adminControllerDistPath . '/' . $filename, $stub);
    }

    public function makeAdminRoute()
    {
        $this->makeRoute($this->adminRouteStubPath, $this->adminRouteDistPath);
    }

    public function makeRoute($routeStubPath, $routeDistPath)
    {
        $placeholder = '#placeholder#';

        $stub = $this->routeStubReplace($routeStubPath);

        $originRoute = file_get_contents($routeDistPath);

        $distRoute = $this->routePadReplace($originRoute, $placeholder, $stub);

        file_put_contents($routeDistPath, $distRoute);
    }

    protected function routeStubReplace($stubPath)
    {
        $class = $this->name;
        $snakeClass = Str::snake($class);
        $title = $this->title;

        $stub = file_get_contents($stubPath);
        return str_replace([
            'group',
            'Group',
            '团队'
        ], [
            $snakeClass,
            $class,
            $title
        ], $stub);
    }

    protected function routePadReplace($origin, $placeholder, $stub)
    {
        $stub = $this->routePadSpace($origin, $placeholder, $stub);

        //移除旧路由
        $origin = str_replace(strstr($stub, $placeholder, true), '', $origin);

        return str_replace($placeholder, $stub, $origin);
    }

    protected function routePadSpace($origin, $placeholder, $stub)
    {
        preg_match("/(.*){$placeholder}/", $origin, $matches);
        $space = $matches[1];

        $stub = $stub . "\n" . $placeholder;
        return preg_replace('/\\n/', "\n" . $space, $stub);
    }

    public function makeIndexController()
    {
        $class = $this->name;
        $controllerClass = $this->name . 'Controller';

        $stub = file_get_contents($this->indexControllerStubPath);
        $stub = str_replace([
            'User',
        ], [
            $class,
        ], $stub);

        $filename = $controllerClass . '.php';
        file_put_contents($this->indexControllerDistPath . '/' . $filename, $stub);
    }

    public function makeIndexRoute()
    {
        $this->makeRoute($this->indexRouteStubPath, $this->indexRouteDistPath);
    }

    public function clean()
    {
        $this->migration && $this->cleanMigration();
        $this->model && $this->cleanModel();
        $this->resource && $this->cleanResource();
        $this->admin && $this->controller && $this->cleanAdminController();
        $this->admin && $this->route && $this->cleanAdminRoute();
        $this->index && $this->controller && $this->cleanIndexController();
        $this->index && $this->route && $this->cleanIndexRoute();
    }

    public function cleanMigration()
    {
        $migrationClass = 'Create' . Str::plural($this->name) . 'Table';

        $filename = '*' . Str::snake($migrationClass) . '.php';

        foreach (glob($this->migrationDistPath . '/' . $filename) as $item) {
            @unlink($item);
        }
    }

    public function cleanModel()
    {
        @unlink($this->modelDistPath . '/' . $this->name . '.php');
    }

    public function cleanResource()
    {
        @unlink($this->resourceDistPath . '/' . $this->name . 'Resource.php');
    }

    public function cleanAdminController()
    {
        @unlink($this->adminControllerDistPath . '/' . $this->name . 'Controller.php');
    }

    public function cleanAdminRoute()
    {
        $this->cleanRoute($this->adminRouteStubPath, $this->adminRouteDistPath);
    }

    public function cleanRoute($routeStubPath, $routeDistPath)
    {
        $placeholder = '#placeholder#';

        $stub = $this->routeStubReplace($routeStubPath);

        $originRoute = file_get_contents($routeDistPath);

        $stub = $this->routePadSpace($originRoute, $placeholder, $stub);

        $origin = str_replace(strstr($stub, $placeholder, true), '', $originRoute);

        file_put_contents($routeDistPath, $origin);
    }

    public function cleanIndexController()
    {
        @unlink($this->indexControllerDistPath . '/' . $this->name . 'Controller.php');
    }

    public function cleanIndexRoute()
    {
        $this->cleanRoute($this->indexRouteStubPath, $this->indexRouteDistPath);
    }
}
