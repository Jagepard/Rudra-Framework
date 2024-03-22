<?php

use Rudra\Container\Rudra;
use DebugBar\StandardDebugBar;
use Rudra\Container\Interfaces\RudraInterface;

return [
    'contracts' => [
        RudraInterface::class => Rudra::run(),
    ],

    'services'  => [
        "DSN" => [PDO::class, [
            config('database', 'dsn'),
            config('database', 'username'),
            config('database', 'password')
        ]],
        "debugbar" => StandardDebugBar::class
    ]
];
