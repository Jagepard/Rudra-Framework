<?php

use Rudra\Container;
use App\Config;

/**
 * Date: 20.02.17
 * Time: 14:54
 *
 * @author    : Korotkov Danila <dankorot@gmail.com>
 * @copyright Copyright (c) 2016, Korotkov Danila
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
 */

return [
    'contracts' => [
        \Rudra\IContainer::class => Container::$app
    ],

    'services' => [
        'debugbar'   => ['DebugBar\StandardDebugBar'],
        'annotation' => ['Rudra\Annotations'],
        'validation' => ['Rudra\Validation'],
        'helper'     => ['App\Helpers\CommonHelper'],
        'auth'       => ['Rudra\Auth'],
        'redirect'   => ['Rudra\Redirect', ['config' => Config::URI]],
        'dbClass'    => ['Rudra\ConnectDB', ['config' => Config::DB]],
        'router'     => ['Rudra\Router', ['namespace' => Config::DEFAULT_NAMESPACE]],
        'route'      => ['App\Route']
    ]
];