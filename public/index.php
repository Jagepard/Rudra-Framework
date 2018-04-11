<?php

use Rudra\URI;
use Rudra\Container as Rudra;
use Symfony\Component\Yaml\Yaml;

/**
 * Environment
 */
define('DEV', false);

/**
 * Base path
 */
define('BP', dirname(__DIR__) . '/');

/**
 * Class Autoloader
 */
require BP . 'vendor/autoload.php';

/**
 * Set APP_URL & PROTOCOL
 */
URI::setup(Rudra::app(), DEV, 'some-host.loc');
Rudra::$app->setConfig(Yaml::parse(file_get_contents(BP . 'app/config.yml')));

/**
 * Set Services
 */
$services = require_once BP . 'app/services.php';
Rudra::$app->setServices($services);

Rudra::$app->get('debugbar')['time']->startMeasure('Index', 'Index');
Rudra::$app->get('route')->run(Rudra::$app->get('router'));
