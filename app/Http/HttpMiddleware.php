<?php

namespace App\Http;

use Rudra\SetContainerTrait;

class HttpMiddleware
{

    use SetContainerTrait;

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

    /**
     * @param $middleware
     */
    protected function next($middleware)
    {
        $this->container()->get('router')->handleMiddleware($middleware, 1);
    }
}
