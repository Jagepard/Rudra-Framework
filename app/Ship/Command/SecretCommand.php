<?php

namespace App\Ship\Command;

use Rudra\Cli\ConsoleFacade as Cli;
use Rudra\Auth\AuthFacade as Auth;
use Rudra\Container\Facades\Request;

class SecretCommand
{
    public function actionIndex(): void
    {
        Request::server()->set([
            "REMOTE_ADDR"     => "127.0.0.1",
            "HTTP_USER_AGENT" => "Mozilla",
        ]);

        Cli::printer(bin2hex(random_bytes(8)) . PHP_EOL, "light_green");
    }
}
