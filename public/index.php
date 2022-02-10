<?php

require '../vendor/autoload.php';

use App\Ship\Route;
use Rudra\Container\Facades\Rudra;
use Rudra\Container\Facades\Request;
use Rudra\Auth\AuthFacade as Auth;

(new Whoops\Run)->appendHandler(new Whoops\Handler\PrettyPageHandler)->register();

Rudra::config((php_sapi_name() == "cli-server")
    ? require_once "../app/Ship/Config/local.php"
    : require_once "../app/Ship/Config/production.php");
Rudra::config()->set(["url" => (php_sapi_name() == "cli-server")
    ? "http://127.0.0.1:8000"
    : Request::server()->get("REQUEST_SCHEME") . "://" . Request::server()->get("SERVER_NAME")]);
Rudra::binding(Rudra::config()->get("contracts"));
Rudra::services(Rudra::config()->get("services"));

if (Rudra::config()->get('environment') === 'development') {
    Rudra::get("debugbar")->addCollector(new DebugBar\DataCollector\PDO\PDOCollector(new DebugBar\DataCollector\PDO\TraceablePDO(Rudra::get("DSN"))));
    Rudra::get("debugbar")->addCollector(new DebugBar\DataCollector\ConfigCollector(Rudra::config()->get()));
}

session_name("RSID_" . Auth::getSessionHash());
Rudra::get(Route::class)->run();

/*
 | php rudra serve to run built-in web server
 | sudo chown -R $USER:www-data 'path to app'
 | sudo chmod -R 755 path to app
 */
