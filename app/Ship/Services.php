<?php

use Rudra\Container\Rudra;
use DebugBar\StandardDebugBar;
use Rudra\Container\Interfaces\RudraInterface;

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
