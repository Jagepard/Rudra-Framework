<?php

namespace App\Containers\Web\Middleware;

use Rudra\Router\MiddlewareInterface;
use Rudra\Router\RouterFacade as Router;

class FirstMiddleware implements MiddlewareInterface
{
    public function __invoke($middlewares)
    {
        dump($middlewares);
        $this->next($middlewares);
    }

    public function next(array $middlewares): void
    {
        Router::handleMiddleware($middlewares);
    }
}
