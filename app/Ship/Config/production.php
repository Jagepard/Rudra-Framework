<?php

use App\Ship\Route;
use DebugBar\StandardDebugBar;
use Rudra\Annotation\Annotation;
use Rudra\Container\Interfaces\RudraInterface;
use Rudra\Container\Rudra;
use Rudra\Redirect\Redirect;

return [
    'app.path'    => '/home/d/custom_projects/php/rudra/Rudra-Framework',
    'environment' => 'development',
    'cache.time'  => 'now',

    'roles'       => [
        'admin'     => 0,
        'editor'    => 1,
        'moderator' => 2,
        'user'      => 3,
    ],

    'containers'  => [
        'web' => 'App\\Containers\\Web\\',
    ],

    'http.errors' => [
        404 => [\App\Ship\Errors\Controllers\HttpErrorsController::class, 'error404'],
        503 => [\App\Ship\Errors\Controllers\HttpErrorsController::class, 'error503'],
    ],

    'contracts'   => [
        RudraInterface::class => Rudra::run(),
    ],

    'services'    => [
        Annotation::class => Annotation::class,
        Route::class      => Route::class,
        Redirect::class   => Redirect::class,
        // "DSN"             => new \PDO('pgsql:host=127.0.0.1;port=5432;dbname=rudra_postgres;', 'jagepard', 'password'),
        // "DSN"             => new \PDO('mysql:dbname=rudra_mysql;host=127.0.0.1', 'jagepard', 'password'),
        "debugbar"        => new StandardDebugBar()
    ]
];
