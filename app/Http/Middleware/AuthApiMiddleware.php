<?php

namespace App\Http\Middleware;

use App\Http\HttpMiddleware;
use Rudra\AuthTrait;

class AuthApiMiddleware extends HttpMiddleware
{

    use AuthTrait;

    public function __invoke($middleware = null)
    {
        $this->auth(false, null, ['API', 'API']);
        $this->next($middleware);
    }
}