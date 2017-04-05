<?php

declare(strict_types = 1);


use Rudra\Container as Rudra;
use Rudra\Helpers;


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

Rudra::app();
Helpers::setUrl(Rudra::app(), DEV, 'lingam.loc');

$app = require_once BP . 'app/app.php';
Rudra::$app->setServices($app);
Rudra::$app->get('route')->run(Rudra::$app->get('router'));
