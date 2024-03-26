<?php // ini_set('zend.exception_ignore_args', 0);

require "../vendor/autoload.php";

use App\Ship\Route;
use Rudra\Auth\Auth;
use Symfony\Component\Yaml\Yaml;
use Rudra\Container\Facades\Rudra;

(new Whoops\Run)->appendHandler(new Whoops\Handler\PrettyPageHandler)->register();

Rudra::config((php_sapi_name() == "cli-server")
    ? Yaml::parseFile("../config/setting.local.yml")
    : Yaml::parseFile("../config/setting.production.yml"));

Rudra::config()->set(["url" => (php_sapi_name() == "cli-server")
    ? "http://127.0.0.1:8000"
    : Rudra::request()->server()->get("REQUEST_SCHEME") . "://" . Rudra::request()->server()->get("SERVER_NAME")]);

Rudra::config()->set(["app.path" => realpath('..')]);
Rudra::config()->set(require_once "../app/Ship/config.php");
Rudra::binding(Rudra::config()->get("contracts"));
Rudra::waiting(Rudra::config()->get("services"));

if (Rudra::config()->get("environment") === "development") {
    Rudra::get("debugbar")->addCollector(new DebugBar\DataCollector\PDO\PDOCollector(new DebugBar\DataCollector\PDO\TraceablePDO(Rudra::get("DSN"))));
    Rudra::get("debugbar")->addCollector(new DebugBar\DataCollector\ConfigCollector(Rudra::config()->get()));
    Rudra::get("debugbar")["time"]->startMeasure("application");
    Rudra::get("debugbar")["time"]->startMeasure("index");
}

session_name("RSID_" . Rudra::get(Auth::class)->getSessionHash());

try {
    Rudra::get(Route::class)->run();
} catch (ArgumentCountError $e) {
    $trace = $e->getTrace()[0];
    Rudra::autowire(Rudra::get($trace['class']), $trace['function']);
} catch (TypeError $e) {
    $trace = $e->getTrace()[0];
    Rudra::autowire(Rudra::new($trace['class']), $trace['function'], $trace['args']);
} 

/*
 | php rudra serve to run built-in web server
 | sudo chown -R $USER:www-data 'path to app'
 | sudo chmod -R 755 path to app
 */
