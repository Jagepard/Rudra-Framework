<?php

namespace App\Web\Middleware;

use App\Web\WebMiddleware;
use Rudra\ExternalTraits\AuthTrait;

class AuthMiddleware extends WebMiddleware
{

    use AuthTrait;

    public function __invoke($middleware = null)
    {
        $this->auth();
        $this->next($middleware);
    }
}
