<?php

namespace App\Http\Middleware;

use App\Http\HttpMiddleware;
use Rudra\AuthTrait;

class AuthMiddleware extends HttpMiddleware
{

    use AuthTrait;

    public function __invoke($middleware = null)
    {
        $this->auth();
        $this->next($middleware);
    }
}