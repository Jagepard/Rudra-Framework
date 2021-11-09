<?php

use App\Route;
use Rudra\Container\Rudra;
use Rudra\Redirect\Redirect;
use Rudra\Annotation\Annotation;
use Rudra\OAuthClient\Provider\Yandex;
use Rudra\OAuthClient\Provider\Github;
use Rudra\OAuthClient\Provider\Google;
use Rudra\Container\Interfaces\RudraInterface;

return [
    'app.path'    => '/app/path',
    'environment' => 'development',
    'cache.time'  => 'now',
    'roles'       => [
        'admin'     => 0,
        'editor'    => 1,
        'moderator' => 2,
        'user'      => 3,
    ],
    'bundles'     => [
        'web'    => 'App\\Web\\',
    ],
    'http.errors' => [
        404 => [\App\Errors\Controllers\HttpErrorsController::class, 'error404'],
        503 => [\App\Errors\Controllers\HttpErrorsController::class, 'error503'],
    ],

    'contracts' => [
        RudraInterface::class => Rudra::run(),
    ],

    'services' => [
        Annotation::class => Annotation::class,
        Route::class      => Route::class,
        Redirect::class   => Redirect::class,
        // "MySQL"           => new \PDO('mysql:dbname=dbname;charset=utf8;host=127.0.0.1', 'user', 'password'),
    ],

    'oauth' => [
        Yandex::class => [
            'client_id'     => '',
            'client_secret' => '',
            'redirect_uri'  => '/oauth?provider=yandex',
        ],
        Github::class => [
            'client_id'     => '',
            'client_secret' => '',
            'redirect_uri'  => '/oauth?provider=github',
        ],
        Google::class => [
            'client_id'     => '',
            'client_secret' => '',
            'redirect_uri'  => '/oauth?provider=google'
        ],
    ],
];
