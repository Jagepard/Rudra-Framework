<?php

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

use Rudra\Container\Rudra;
use DebugBar\StandardDebugBar;
use Rudra\Container\Interfaces\RudraInterface;

return [
    'contracts' => [
        RudraInterface::class => Rudra::run(),
    ],

    'services'  => [
        'connection' => [PDO::class, [
            config('database', 'dsn'),
            config('database', 'username'),
            config('database', 'password')
        ]],
        "debugbar" => StandardDebugBar::class
    ]
];
