<?php

namespace App\Http\Middleware;

use App\Http\BaseMiddleware;

class MainMiddleware extends BaseMiddleware
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