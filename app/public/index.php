<?php

/**
 * Enviroment
 */
define('DEV', true);

/**
 * Base path
 */
define('BP', dirname(dirname(__DIR__)) . '/');

/**
 * Class Autoloader
 */
require BP . 'vendor/autoload.php';

/**
 * Базовый url
 */
(DEV) ? getUrl() : getUrl('example.com');

$di = new \Rudra\Container();

$di->set('dbClass', new \Rudra\DB($di, \App\Config\Config::DB));
$di->set('redirect', new \Rudra\Redirect($di, \App\Config\Config::URI));
$di->set('validation', new \Rudra\Validation());
$di->set('auth', new \Rudra\Auth($di, App\Config\Config::ADMIN));
$di->set('annotation', new \Rudra\Annotations());

$app = new \App\Config\Routing(
    new \Rudra\Router($di, \App\Config\Config::DEFAULT_NAMESPACE)
);

$app->run();