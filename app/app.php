<?php

use Rudra\ContainerInterface;
use Rudra\Container;
use App\Config;

/**
 * Возвращает массив контрактов и сервисов
 * Контракты связывают интерфейсы с реализацией
 */
return [
    'contracts' => [
        ContainerInterface::class => Container::$app
    ],

    'services' => [
        'debugbar'   => ['DebugBar\StandardDebugBar'],
        'annotation' => ['Rudra\Annotations'],
        'validation' => ['Rudra\Validation'],
        'auth'       => ['Rudra\Auth'],
        'redirect'   => ['Rudra\Redirect',  ['config'    => Config::URI]],
        'dbClass'    => ['Rudra\ConnectDB', ['config'    => Config::DB]],
        'router'     => ['Rudra\Router',    ['namespace' => Config::DEFAULT_NAMESPACE, 'templateEngine' => Config::TE]],
        'route'      => ['App\Route']
    ]
];
