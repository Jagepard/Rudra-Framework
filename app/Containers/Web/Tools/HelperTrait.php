<?php

namespace App\Containers\Web\Tools;

use Rudra\Container\Facades\Rudra;

trait HelperTrait
{
    public function info(string $message): void
    {
        Rudra::get("debugbar")['messages']->info($message);
    }
}
