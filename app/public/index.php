<?php

use Rudra\Container as Rudra;
use Rudra\Helpers;
use Symfony\Component\Yaml\Yaml;

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
Rudra::$app->setConfig(Yaml::parse(file_get_contents(BP . 'app/config.yml')));
Helpers::setUrl(Rudra::$app, DEV, 'lingam.loc');

$app = require_once BP . 'app/app.php';

Rudra::$app->setServices($app);
Rudra::$app->get('route')->run(Rudra::$app->get('router'));
