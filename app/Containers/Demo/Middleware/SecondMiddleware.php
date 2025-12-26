<?php

namespace App\Containers\Demo\Middleware;

use App\Containers\Demo\Tools\HelperTrait;

class SecondMiddleware
{
    use HelperTrait;

    public function __invoke(array $next): void
    {
        $this->info(__CLASS__);

        if (!$next) {
            $next();
        }
    }
}
