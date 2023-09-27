<?php

require "../vendor/autoload.php";

use App\Ship\Route;
use Rudra\Auth\Auth;
use Rudra\Container\Rudra;
use Symfony\Component\Yaml\Yaml;

(new Whoops\Run)->appendHandler(new Whoops\Handler\PrettyPageHandler)->register();

Rudra::run()->config((php_sapi_name() == "cli-server")
    ? Yaml::parseFile("../config/setting.local.yml")
    : Yaml::parseFile("../config/setting.production.yml"));

Rudra::run()->config()->set(["url" => (php_sapi_name() == "cli-server")
    ? "http://127.0.0.1:8000"
    : Rudra::run()->request()->server()->get("REQUEST_SCHEME") . "://" . Rudra::run()->request()->server()->get("SERVER_NAME")]);

Rudra::run()->config()->set(require_once "../app/Ship/Services.php");
Rudra::run()->binding(Rudra::run()->config()->get("contracts"));
Rudra::run()->serviceList(Rudra::run()->config()->get("services"));

if (Rudra::run()->config()->get("environment") === "development") {
    Rudra::run()->get("debugbar")->addCollector(new DebugBar\DataCollector\PDO\PDOCollector(new DebugBar\DataCollector\PDO\TraceablePDO(Rudra::run()->get("DSN"))));
    Rudra::run()->get("debugbar")->addCollector(new DebugBar\DataCollector\ConfigCollector(Rudra::run()->config()->get()));
    Rudra::run()->get("debugbar")["time"]->startMeasure("application");
    Rudra::run()->get("debugbar")["time"]->startMeasure("index");
}

session_name("RSID_" . Rudra::run()->get(Auth::class)->getSessionHash());

Rudra::run()->get(Route::class)->run();

/*
 | php rudra serve to run built-in web server
 | sudo chown -R $USER:www-data 'path to app'
 | sudo chmod -R 755 path to app
 */
