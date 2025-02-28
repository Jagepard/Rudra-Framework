<?php

namespace App\Containers\Tools;

use Rudra\Container\Facades\Rudra;

trait HelperTrait
{
    public function info(string $message): void
    {
        Rudra::get("debugbar")['messages']->info($message);
    }
}
