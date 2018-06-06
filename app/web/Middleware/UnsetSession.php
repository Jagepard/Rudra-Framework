<?php

namespace App\Web\Middleware;

use App\Web\WebMiddleware;

class UnsetSession extends WebMiddleware
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
