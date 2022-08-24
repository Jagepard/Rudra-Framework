<?php

use App\Ship\Route;
use DebugBar\StandardDebugBar;
use Rudra\Annotation\Annotation;
use Rudra\Container\Interfaces\RudraInterface;
use Rudra\Container\Facades\Rudra;
use Rudra\Redirect\Redirect;

return [
    'contracts'   => [
        RudraInterface::class => Rudra::run(),
    ],

    'services'    => [
        Annotation::class => Annotation::class,
        Route::class      => Route::class,
        Redirect::class   => Redirect::class,
        
        // "DSN"             => new \PDO('pgsql:host=127.0.0.1;port=5432;dbname=rudra_postgres;', 'jagepard', 'password'),
        "DSN"             => new \PDO(
            'mysql:dbname=rudra_mysql;host=127.0.0.1', 
            Rudra::config()->get('dbuser')['username'], 
            Rudra::config()->get('dbuser')['password']
        ),
        // "DSN"             => new PDO('sqlite:/home/d/custom_projects/php/rudra/Rudra-Framework/app/Ship/Data/rudra.sqlite'),

        "debugbar"        => new StandardDebugBar()
    ]
];