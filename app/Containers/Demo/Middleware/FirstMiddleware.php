<?php

namespace App\Containers\Demo\Middleware;

use Rudra\Router\MiddlewareInterface;
use Rudra\Router\RouterFacade as Router;
use App\Containers\Demo\Tools\HelperTrait;

class FirstMiddleware implements MiddlewareInterface
{
    use HelperTrait;

    public function __invoke($middlewares): void
    {
        $this->info(__CLASS__);
        $this->next($middlewares);
    }

    public function next(array $chainOfMiddlewares): void
    {
        Router::handleMiddleware($chainOfMiddlewares);
    }
}
