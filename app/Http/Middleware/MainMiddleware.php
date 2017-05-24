<?php

namespace App\Http\Middleware;

use App\Http\HttpMiddleware;

class MainMiddleware extends HttpMiddleware
{

    public function __invoke($current, $middleware = null)
    {
        // StartMiddleware

        !d($current);

        // EndMiddleware

        $this->next($middleware);
    }
}