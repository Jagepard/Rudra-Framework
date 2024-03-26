<?php

use Rudra\Router\RouterFacade as Router;

Router::get([
    'url' => "callable/:name",
    'controller' => function ($name) {
        echo "Hello $name!";
    }
]);

return [
    \App\Containers\Web\Controller\IndexController::class,
];
