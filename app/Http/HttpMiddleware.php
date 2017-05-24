<?php

namespace App\Http;

use Rudra\SetContainerTrait;

class HttpMiddleware
{

    use SetContainerTrait;

    protected function next($middleware)
    {
        $this->container()->get('router')->handleMiddleware($middleware);
    }
}
