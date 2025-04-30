<?php

namespace App\Containers\Demo\Middleware;

use Rudra\Router\MiddlewareInterface;
use App\Containers\Demo\Tools\HelperTrait;

class SecondMiddleware implements MiddlewareInterface
{
    use HelperTrait;

    public function __invoke(array $chainOfMiddlewares): void
    {
        $this->info(__CLASS__);
    }

    public function next(array $chainOfMiddlewares): void
    {

    }
}
