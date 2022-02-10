<?php

namespace App\Containers\Web\Middleware;

use Rudra\Router\MiddlewareInterface;
use Rudra\Router\RouterFacade as Router;

class FirstMiddleware implements MiddlewareInterface
{
    public function __invoke($middlewares)
    {
        $this->info(__CLASS__);
        $this->next($middlewares);
    }

    public function next(array $middlewares): void
    {
        Router::handleMiddleware($middlewares);
    }

    public function info($message)
    {
        echo "<div class=\"alert alert-primary\">$message</div>";
    }
}
