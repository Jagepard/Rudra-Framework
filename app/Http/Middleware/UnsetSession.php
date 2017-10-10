<?php

namespace App\Http\Middleware;

use App\Http\HttpMiddleware;

class UnsetSession extends HttpMiddleware
{

    public function __invoke($current, $middleware = null)
    {
        // StartMiddleware

        $this->container()->unsetSession('value');
        $this->container()->unsetSession('alert');

        // EndMiddleware

        $this->next($middleware);
    }
}