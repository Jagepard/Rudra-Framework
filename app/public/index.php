<<?php

use \Rudra\Container as Rudra;

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
getUrl('jagepard.ru');

Rudra::app();
Rudra::$app->set('debugbar', new \DebugBar\StandardDebugBar());
Rudra::$app->get('debugbar')['time']->startMeasure('Index', 'Index');
Rudra::$app->set('annotation', new \Rudra\Annotations());
Rudra::$app->set('validation', new \Rudra\Validation());
Rudra::$app->set('auth', new \Rudra\Auth(Rudra::$app));
Rudra::$app->set('redirect', new \Rudra\Redirect(Rudra::$app, \App\Config\Config::URI));
Rudra::$app->set('dbClass', new \Rudra\DB(Rudra::$app, \App\Config\Config::DB));
Rudra::$app->set('router', new \Rudra\Router(Rudra::$app, \App\Config\Config::DEFAULT_NAMESPACE));
Rudra::$app->set('routing', new \App\Config\Routing());
Rudra::$app->get('routing')->run(Rudra::$app->get('router'));
