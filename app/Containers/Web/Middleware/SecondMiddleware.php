<?php

namespace App\Containers\Web\Middleware;

use Rudra\Router\MiddlewareInterface;

class SecondMiddleware implements MiddlewareInterface
{
    public function __invoke(array $middleware): void
    {
        $this->info(__CLASS__);
    }

    public function next(array $middlewares): void
    {

    }

    public function info($message)
    {
        echo "<div class=\"alert alert-secondary\">$message</div>";
    }
}
