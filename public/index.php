<?php

session_name("RudraFramework");

require '../vendor/autoload.php';

//(new Whoops\Run)->pushHandler(new Whoops\Handler\PrettyPageHandler)->register();

rudra()->setConfig(Symfony\Component\Yaml\Yaml::parse(file_get_contents('../app/config.yml')));
rudra()->addConfig('url',  (php_sapi_name() == 'cli-server') ? 'http://127.0.0.1:8000'
    : rudra()->getServer('REQUEST_SCHEME') . "://" . rudra()->getServer('SERVER_NAME'));
rudra()->setServices(require_once '../app/services.php'); // Set Services
rudra()->get('debugbar')['time']->startMeasure('Index', 'Index');
rudra()->get('route')->run();

/*
 * cd public
 * php -S localhost:8000
 * to run built-in web server
 */
