<?php

namespace App\Web\Middleware;

use App\Web\WebMiddleware;

class MainMiddleware extends WebMiddleware
{

    public function __invoke($current, $middleware = null)
    {
        // StartMiddleware

        !d($current);

        // EndMiddleware

        $this->next($middleware);
    }
}