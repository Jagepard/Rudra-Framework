<?php

namespace App\Http\Middleware;

use App\Http\HttpMiddleware;

class MainMiddleware extends HttpMiddleware
{

    /**
     * @param null $middleware
     *
     * @return bool
     */
    public function __invoke($middleware = null)
    {
        // StartMiddleware

        if ($middleware[0][1]['int'] % 2) {
            echo json_encode($_SERVER);
        }

        // EndMiddleware

        $this->next($middleware);
    }
}