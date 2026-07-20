<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

require "../vendor/autoload.php";

use App\Ship\Route;
use App\Ship\Utils\RudraDumper;
use Rudra\Auth\Auth;
use Symfony\Component\Yaml\Yaml;
use Rudra\Container\Facades\Rudra;

RudraDumper::register();
(new Whoops\Run)->appendHandler(new Whoops\Handler\PrettyPageHandler)->register();

$env = match (true) {
    getenv('IS_DDEV_PROJECT') === 'true' => 'ddev',
    php_sapi_name() === 'cli-server' => 'local',
    default  => 'production',
};

Rudra::config(Yaml::parseFile(__DIR__ . "/../config/setting.{$env}.yml"));

// Respect reverse proxy headers (nginx, Cloudflare) for correct URL scheme
Rudra::config()->set(['url' => php_sapi_name() === 'cli-server' 
    ? 'http://127.0.0.1:8000' 
    : (Rudra::request()->server()->get('HTTP_X_FORWARDED_PROTO') ?? 'http') 
        . '://' . Rudra::request()->server()->get('SERVER_NAME')
]);

Rudra::config()->set(["app.path" => realpath('..')]);
Rudra::config()->set(require_once "../app/Ship/config.php");
Rudra::binding(Rudra::config()->get("contracts"));
Rudra::waiting(Rudra::config()->get("services"));

if (Rudra::config()->get("environment") === "development") {
    $debugbar = Rudra::get("debugbar");
    $debugbar->addCollector(new DebugBar\DataCollector\PDO\PDOCollector(new DebugBar\DataCollector\PDO\TraceablePDO(Rudra::get("connection"))));
    $debugbar->addCollector(new App\Ship\Utils\SecurityCollector());
    $debugbar->addCollector(new App\Ship\Utils\RoutingCollector());
    
    if (in_array($env, ['ddev', 'local'], true)) {
        $debugbar->addCollector(new DebugBar\DataCollector\ConfigCollector(Rudra::config()->all()));
    }

    $debugbar["time"]->startMeasure("application");
    $debugbar["time"]->startMeasure("index");
} 

// Prefix "RSID" = Rudra Session ID
// Application-wide stable session name (independent of user IP and User-Agent)
session_name("RSID_" . substr(hash('sha256', Rudra::config()->get('secret')), 0, 12));

Rudra::get(Route::class)->run();

/*
 | php rudra serve to run built-in web server
 | sudo chown -R $USER:www-data 'path to app'
 | sudo chmod -R 755 path to app
 */
