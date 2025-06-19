<?php

namespace App\Ship\Command;

use Rudra\Cli\ConsoleFacade as Cli;
use Rudra\Auth\AuthFacade as Auth;
use Rudra\Container\Facades\Request;

class BcryptCommand
{
    public function actionIndex(): void
    {
        Request::server()->set([
            "REMOTE_ADDR"     => "127.0.0.1",
            "HTTP_USER_AGENT" => "Mozilla",
        ]);

        Cli::printer("Enter password: ", "magneta");
        $password = trim(fgets(fopen("php://stdin", "r")));

        Cli::printer(Auth::bcrypt($password) . PHP_EOL, "light_green", );
    }
}
