<?php

use Rudra\Container;
use Rudra\Interfaces\ContainerInterface;

$app = Container::$app;

return [
    'contracts' => [
        ContainerInterface::class => Container::$app,
    ],

    'services' => [
        'debugbar'   => ['DebugBar\StandardDebugBar'],
        'annotation' => ['Rudra\Annotation'],
        'validation' => ['Rudra\Validation'],
        'auth'       => ['Rudra\Auth', ['env' => $app->config('env'), 'role' => $app->config('role')]],
        'redirect'   => ['Rudra\Redirect', ['url' => APP_URL, 'env' => $app->config('env')]],
        'connector'  => ['Rudra\Connector', ['config' => $app->config('database')]],
        'router'     => ['Rudra\Router', ['namespace' => $app->config('namespaces', 'default')]],
        'route'      => ['App\Route']
    ]
];
