<?php

namespace App\Containers\Web\Middleware;

use App\Ship\Utils\HelperTrait;
use Rudra\Router\MiddlewareInterface;
use Rudra\Router\RouterFacade as Router;

class FirstMiddleware implements MiddlewareInterface
{
    use HelperTrait;

    public function __invoke($middlewares)
    {
        $this->info(__CLASS__);
        $this->next($middlewares);
    }

    public function next(array $middlewares): void
    {
        Router::handleMiddleware($middlewares);
    }
}
