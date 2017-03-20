<?php

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
getUrl('lingam.loc');

Rudra::app();
$app = require_once BP . 'app/app.php';
Rudra::$app->setServices($app);
Rudra::$app->get('route')->run(Rudra::$app->get('router'));
