<?php

use Rudra\Router\RouterFacade as Router;

Router::annotationCollector([
    \App\Web\Controllers\MainController::class,
]);
