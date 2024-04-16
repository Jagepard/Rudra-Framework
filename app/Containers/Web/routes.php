<?php

use Rudra\Router\RouterFacade as Router;

if (php_sapi_name() != "cli") {
    Router::get([
        'url' => "callable/:name",
        'controller' => function ($name) {
            echo "Hello $name!";
        }
    ]);
}

return [
    \App\Containers\Web\Controller\IndexController::class,
];
