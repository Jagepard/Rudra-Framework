<?php

use Rudra\Router\RouterFacade as Router;

if (php_sapi_name() != "cli") {
    Router::get("callable/:name", function ($name) {
            echo "Hello $name!";
        }
    );
}

return [
    \App\Containers\Demo\Controller\IndexController::class,
];
