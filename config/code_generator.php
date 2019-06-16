<?php
$path = dir(__DIR__);

return [
    'namespace' => 'Modules\Common\Entities',

    'modelStubPath' => $path . '/src/stubs/model.stub',
    'modelDistPath' => 'Modules/Common/Entities',

    'migrationStubPath' => $path . '/src/stubs/migration.stub',
    'migrationDistPath' => 'database/migrations',

    'resourceStubPath' => $path . '/src/stubs/resource.stub',
    'resourceDistPath' => 'Modules/Common/Transformers',

    'adminControllerStubPath' => $path . '/src/stubs/admin_controller.stub',
    'adminControllerDistPath' => 'Modules/Admin/Http/Controllers',

    'adminRouteStubPath' => $path . '/src/stubs/admin_route.stub',
    'adminRouteDistPath' => 'Modules/Admin/Routes/web.php',

    'indexControllerStubPath' => $path . '/src/stubs/index_controller.stub',
    'indexControllerDistPath' => 'Modules/Index/Http/Controllers',

    'indexRouteStubPath' => $path . '/src/stubs/index_route.stub',
    'indexRouteDistPath' => 'Modules/Index/Routes/web.php',
];
