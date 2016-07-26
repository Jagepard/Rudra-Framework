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

$di->set('dbClass', new \Rudra\DB($di, \Config\Config::DB));

$di->set('redirect', new \Core\Redirect(\Config\Config::URI));

$di->set('validation', new \Rudra\Validation(\Config\Config::CAPTHA_SECRET));

$app = new \Config\Routing(
    new \Rudra\Router($di)
);

$app->run();
