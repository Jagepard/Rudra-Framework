<?php

namespace App\Containers\Web\Middleware;

use App\Ship\Utils\HelperTrait;
use Rudra\Router\MiddlewareInterface;

class SecondMiddleware implements MiddlewareInterface
{
    use HelperTrait;

    public function __invoke(array $middleware): void
    {
        $this->info(__CLASS__);
    }

    public function next(array $middlewares): void
    {

    }
}
