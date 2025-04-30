<?php

namespace App\Containers\Demo\Tools;

use Rudra\Container\Facades\Rudra;

trait HelperTrait
{
    public function info(string $message): void
    {
        Rudra::get("debugbar")['messages']->info($message);
    }
}
