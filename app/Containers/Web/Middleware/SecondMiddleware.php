<?php

namespace App\Containers\Web\Middleware;

use App\Containers\Tools\HelperTrait;
use Rudra\Router\MiddlewareInterface;

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
