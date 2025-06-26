<?php

namespace App\Containers\Demo\Middleware;

use App\Containers\Demo\Tools\HelperTrait;

class FirstMiddleware
{
    use HelperTrait;

    public function __invoke($next): void
    {
        $this->info(__CLASS__);

        if (!$next) {
            $next();
        }
    }
}
