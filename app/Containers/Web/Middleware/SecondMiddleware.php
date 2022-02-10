<?php

namespace App\Containers\Web\Middleware;

use Rudra\Router\MiddlewareInterface;

class SecondMiddleware implements MiddlewareInterface
{
    public function __invoke(array $middleware): void
    {
        var_dump(123);
    }

    public function next(array $middlewares): void
    {

    }
}
