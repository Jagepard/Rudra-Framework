<?php

use Rudra\Container\Rudra;
use DebugBar\StandardDebugBar;
use App\Containers\Web\Factory\StdFactory;
use App\Containers\Web\Factory\TestFactory;
use Rudra\Container\Interfaces\RudraInterface;
use App\Containers\Web\Interface\TestInterface;

return [
    'contracts'   => [
        RudraInterface::class => Rudra::run(),
    ],

    'services'    => [
        "DSN" => [PDO::class, [
            Rudra::run()->config()->get('database')['dsn'], 
            Rudra::run()->config()->get('database')['username'], 
            Rudra::run()->config()->get('database')['password']]
        ],
        "debugbar" => StandardDebugBar::class
    ]
];
