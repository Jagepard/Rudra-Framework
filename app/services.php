<?php

use Rudra\ContainerInterface;
use Rudra\Container;

return [
    'contracts' => [
        ContainerInterface::class => Container::$app,
    ],

    'services' => [
        'debugbar'   => ['DebugBar\StandardDebugBar'],
        'annotation' => ['Rudra\Annotations'],
        'validation' => ['Rudra\Validation'],
        'auth'       => ['Rudra\Auth'],
        'redirect'   => ['Rudra\Redirect', ['config' => Container::$app->config('uri')]],
        'dbClass'    => ['Rudra\ConnectDB', ['config' => Container::$app->config('database')]],

        'router' => [
            'Rudra\Router', [
                'namespace'      => Container::$app->config('namespaces', 'default'),
                'templateEngine' => Container::$app->config('template')
            ]
        ],

        'route' => ['App\Route']
    ]
];