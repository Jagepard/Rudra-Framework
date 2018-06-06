<?php

namespace App\Web;

use Rudra\ExternalTraits\SetContainerTrait;

class WebMiddleware
{

    use SetContainerTrait;

    protected function next($middleware)
    {
        $this->container()->get('router')->handleMiddleware($middleware);
    }
}
